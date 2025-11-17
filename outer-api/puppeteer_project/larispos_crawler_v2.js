// server.js
const express = require("express");
const puppeteer = require("puppeteer");

const app = express();
const port = 3005;

process.env.PUPPETEER_SKIP_CHROMIUM_DOWNLOAD = "true";

app.get("/larisposv2", async (req, res) => {
  const browser = await puppeteer.launch({
    // headless: false,
    executablePath:
      "C:\\Users\\USER\\.cache\\puppeteer\\chrome\\win64-119.0.6045.105\\chrome-win64\\chrome.exe",
  });
  try {
    // Mendapatkan parameter dari baris perintah

    const id_toko = req.query.id_toko;
    const username = req.query.username;
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

    // await page.setRequestInterception(true);

    // page.on("request", (req) => {
    //   if (req.resourceType() == "stylesheet" || req.resourceType() == "image") {
    //     req.abort();
    //   } else {
    //     req.continue();
    //   }
    // });

    await page.goto("https://www.larispos.com/app/acc/login_user");
    await page.setDefaultNavigationTimeout(60000);

    // Mendapatkan elemen input untuk username dan password dan memasukkan nilai
    await page.type("input[name=txt_publiccode]", id_toko);
    await page.type("input[name=txt_username]", username);
    await page.type("input[name=txt_password]", pass);

    // Klik tombol login
    await page.click("input[type=submit]");

    await page.setDefaultNavigationTimeout(60000);
    // Tunggu hingga proses login selesai (mungkin perlu menyesuaikan waktu sesuai dengan kebutuhan)
    // await page.waitForNavigation();

    await page.goto(
      "https://www.larispos.com/app/sales_reports/listing?exportfilter=0&date_start=" +
        start_date +
        "&date_end=" +
        end_date +
        "&store=" +
        q_store +
        "&trx=-%2F-&transtype=&status=&user=&show_detail=0&show_shift=&show_split=0"
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
