// server.js
const express = require("express");
const puppeteer = require("puppeteer");

const app = express();
const port = 3000;

app.get("/pawoon", async (req, res) => {
  const browser = await puppeteer.launch({ headless: true });
  try {
    // Mendapatkan parameter dari baris perintah

    const email = req.query.email;
    const pass = req.query.pass;
    const start_date = req.query.start_date;
    const end_date = req.query.end_date;

    const page = await browser.newPage();

    // Mengakses DevTools Protocol
    const client = await page.target().createCDPSession();

    // Mengatur CacheDisabled menjadi false (menyala)
    await client.send("Network.setCacheDisabled", { cacheDisabled: false });

    await page.setRequestInterception(true);

    page.on("request", (req) => {
      if (req.resourceType() == "stylesheet" || req.resourceType() == "image") {
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
      "https://dashboard.pawoon.com/report/sales-transaction/data?page=1&sort_by=desc&start_date=" +
        start_date +
        "&end_date=" +
        end_date +
        "&utc_offset=-7"
    );

    await page.waitForSelector("pre");

    // Mengambil teks dari elemen <pre>
    const jsonText = await page.$eval(
      "pre",
      (preElement) => preElement.textContent
    );

    // Menampilkan data JSON di console
    console.log(jsonText);

    // Set Content-Type header to application/json
    res.header("Content-Type", "application/json");

    const jsonParse = JSON.parse(jsonText);

    res.json(jsonParse);
  } catch (error) {
    console.error("Error during scraping:", error);
    res.status(500).send("Internal Server Error");
  }
  await browser.close();
});

app.listen(port, () => {
  console.log(`Server listening at http://localhost:${port}`);
});
