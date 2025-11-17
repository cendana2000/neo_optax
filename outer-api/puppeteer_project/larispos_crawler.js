// server.js
const express = require("express");
const puppeteer = require("puppeteer");

const app = express();
const port = 3003;
// const port = 3010;

process.env.PUPPETEER_SKIP_CHROMIUM_DOWNLOAD = "true";

app.get("/larispos", async (req, res) => {
  const browser = await puppeteer.launch({
    // headless: false,
    executablePath:
      "C:\\Users\\USER\\.cache\\puppeteer\\chrome\\win64-119.0.6045.105\\chrome-win64\\chrome.exe",
  });
  try {
    // Mendapatkan parameter dari baris perintah

    const email = req.query.email;
    const pass = req.query.pass;
    const start_date = req.query.start_date;
    const end_date = req.query.end_date;
    const halaman = req.query.halaman;
    const q_store = req.query.q_store;

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

    await page.goto("https://www.larispos.com/app/acc/login");
    await page.setDefaultNavigationTimeout(60000);

    // Mendapatkan elemen input untuk username dan password dan memasukkan nilai
    await page.type("input[name=txt_username]", email);
    await page.type("input[name=txt_password]", pass);

    // Klik tombol login
    await page.click("input[type=submit]");

    await page.setDefaultNavigationTimeout(60000);
    // Tunggu hingga proses login selesai (mungkin perlu menyesuaikan waktu sesuai dengan kebutuhan)
    // Await page.waitForNavigation();

    await page.goto(
      "https://www.larispos.com/app/transaction/listing?q_page=" +
        halaman +
        "&q_sortby=&q_sort=&q_customer=&q_trx=-1&page_size=100000&q_sdate=" +
        start_date +
        "&q_edate=" +
        end_date +
        "&q_trx=-1&q_store=" +
        q_store +
        "&q_type=-1&q_status=100&q_search=&q_customer="
    );
    await page.setDefaultNavigationTimeout(60000);

    const responseBody = await page.content();
    res.status(200).send(responseBody);
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
