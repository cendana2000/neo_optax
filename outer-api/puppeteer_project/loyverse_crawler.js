// server.js
const express = require("express");
const puppeteer = require("puppeteer");

const app = express();
const port = 3004;

process.env.PUPPETEER_SKIP_CHROMIUM_DOWNLOAD = "true";

app.get("/loyverse", async (req, res) => {
  const browser = await puppeteer.launch({
    headless: false,
    executablePath:
      "C:\\Users\\USER\\.cache\\puppeteer\\chrome\\win64-119.0.6045.105\\chrome-win64\\chrome.exe",
  });
  try {
    // Mendapatkan parameter dari baris perintah

    // const namausaha = req.query.namausaha;
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
      //if (
      // req.resourceType() == "stylesheet" ||
      // req.resourceType() == "image" //||
      // req.resourceType() == "javascript"
      //) {
      //  req.abort();
      // } else {
      req.continue();
      // }
    });

    await page.goto("https://loyverse.com/en/login");
    await page.setDefaultNavigationTimeout(60000);

    // Mendapatkan elemen input untuk username dan password dan memasukkan nilai
    await page.waitForSelector("input[type=text]");
    await page.type("input[type=text]", email);
    await page.type("input[type=password]", pass);

    // // Klik tombol login
    await page.click("button[type=submit]");

    await page.setDefaultNavigationTimeout(300000);
    // Tunggu hingga proses login selesai (mungkin perlu menyesuaikan waktu sesuai dengan kebutuhan)
    await page.waitForNavigation();

    await page.goto(
      "https://r.loyverse.com/dashboard/#/report/average?page=0&limit=10000&periodName=month&periodLength=1m&arg=0&from=" +
        start_date +
        "%2000:00:00&to=" +
        end_date +
        "%2023:59:59&fromHour=0&toHour=0&type=all&outletsIds=all&merchantsIds=all"
    );

    // https://r.loyverse.com/dashboard/#/report/average?page=0&limit=10000&periodName=month&periodLength=1d&arg=0&from=2025-07-31%2000:00:00&to=2025-07-31%2023:59:59&fromHour=0&toHour=0&type=all&outletsIds=all&merchantsIds=all

    await page.setDefaultNavigationTimeout(60000);

    // Mengatur handler untuk event response
    page.on("response", async (response) => {
      // URL permintaan yang ingin Anda tangkap responsnya
      const targetUrlPrefix =
        "https://r.loyverse.com/data/ownercab/getreceiptsarchive";

      const responseUrl = response.url();

      // Periksa apakah URL respons dimulai dengan targetUrlPrefix
      if (responseUrl.startsWith(targetUrlPrefix)) {
        // Tangkap isi respons
        const responseBody = await response.text();
        // console.log(`Response body from ${targetUrlPrefix}:`, responseBody);

        const jsonParse = JSON.parse(responseBody);

        // Log the HTML content to the console
        // console.log(htmlContent);
        // res.header("Content-Type", "text/html");
        res.status(200).json(jsonParse);

        // await browser.close();
      }

      //   console.log(`Response URL: ${response.url()}`);
      //   console.log(`Response Status: ${response.status()}`);
      //   console.log(`Response OK: ${response.ok()}`);
    });
  } catch (error) {
    console.error("Error during scraping:", error);
    res.status(500).send("Internal Server Error");

    // await browser.close();
  }
});

app.listen(port, "127.0.0.1", () => {
  console.log(`Server listening at http://127.0.0.1:${port}`);
});
