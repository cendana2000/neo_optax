// server.js
const express = require("express");
const puppeteer = require("puppeteer");

const app = express();
const port = 3000;

process.env.PUPPETEER_SKIP_CHROMIUM_DOWNLOAD = "true";

app.get("/pawoon", async (req, res) => {
  const browser = await puppeteer.launch({
    // headless: false,
    executablePath:
      "C:\\Users\\USER\\.cache\\puppeteer\\chrome\\win64-119.0.6045.105\\chrome-win64\\chrome.exe",
  });
  try {
    // Mendapatkan parameter dari baris perintah

    //C:\Users\USER\.cache\puppeteer\chrome\win64-119.0.6045.105\chrome-win64\chrome.exe

    const email = req.query.email;
    const pass = req.query.pass;
    const start_date = req.query.start_date;
    const end_date = req.query.end_date;
    const halaman = req.query.halaman;

    const page = await browser.newPage();

    // Mengakses DevTools Protocol
    const client = await page.target().createCDPSession();

    // Mengatur CacheDisabled menjadi false (menyala)
    await client.send("Network.setCacheDisabled", { cacheDisabled: false });

    await page.setRequestInterception(true);

    page.on("request", (req) => {
      if (
        req.resourceType() == "stylesheet" ||
        req.resourceType() == "image" ||
        req.resourceType() == "script"
      ) {
        req.abort();
      } else {
        req.continue();
      }
    });

    await page.goto("https://dashboard.pawoon.com/login");
    await page.setDefaultNavigationTimeout(60000);

    // Mendapatkan elemen input untuk username dan password dan memasukkan nilai
    await page.type("input[name=email]", email);
    await page.type("input[name=password]", pass);

    // Klik tombol login
    await page.click("#do-login");

    await page.setDefaultNavigationTimeout(10000);
    // Tunggu hingga proses login selesai (mungkin perlu menyesuaikan waktu sesuai dengan kebutuhan)
    //   await page.waitForNavigation();

    await page.goto(
      "https://dashboard.pawoon.com/report/sales-transaction/data?page=" +
        halaman +
        "&sort_by=desc&start_date=" +
        start_date +
        "&end_date=" +
        end_date +
        // "&utc_offset=-7&status=success"
        "&status=success&utc_offset=-7"
    );

    // Get the HTML content of the page
    const htmlContent = await page.content();

    await page.waitForSelector("pre");

    // Mengambil teks dari elemen <pre>
    const jsonText = await page.$eval(
      "pre",
      (preElement) => preElement.textContent
    );

    // Menampilkan data JSON di console
    // console.log(jsonText);

    // Set Content-Type header to application/json
    res.header("Content-Type", "application/json");

    const jsonParse = JSON.parse(jsonText);

    // Log the HTML content to the console
    // console.log(htmlContent);
    res.status(200).json(jsonParse);

    await browser.close();
  } catch (error) {
    console.error("Error during scraping:", error);
    res.status(500).send("Internal Server Error");

    await browser.close();
  }
});

app.listen(port, "127.0.0.1", () => {
  console.log(`Server listening at http://127.0.0.1:${port}`);
});
