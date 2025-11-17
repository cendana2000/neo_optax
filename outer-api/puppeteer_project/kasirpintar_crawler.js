// server.js
const express = require("express");
const puppeteer = require("puppeteer");

const app = express();
const port = 3005;

process.env.PUPPETEER_SKIP_CHROMIUM_DOWNLOAD = "true";

app.get("/kasirpintar", async (req, res) => {
  const browser = await puppeteer.launch({
    headless: false,
    executablePath:
      "C:\\Users\\USER\\.cache\\puppeteer\\chrome\\win64-119.0.6045.105\\chrome-win64\\chrome.exe",
    args: [
      "--start-maximized", // This will start the browser maximized
    ],
  });
  try {
    // Mendapatkan parameter dari baris perintah

    const email = req.query.email;
    const pass = req.query.pass;
    const start_date = req.query.start_date;
    const end_date = req.query.end_date;

    const page = await browser.newPage();

    // Set the viewport to a large size
    await page.setViewport({ width: 1280, height: 1080 });

    // Set the user agent to a custom string
    const customUserAgent =
      "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36";
    // await page.setUserAgent(customUserAgent);

    // Mengakses DevTools Protocol
    // const client = await page.target().createCDPSession();

    // Mengatur CacheDisabled menjadi false (menyala)
    // await client.send("Network.setCacheDisabled", { cacheDisabled: false });

    await page.setRequestInterception(false);

    page.on("request", (req) => {
      //   if (req.resourceType() == "script") {
      //     req.abort();
      //   } else {
      //     req.continue();
      //   }
    });

    await page.goto("https://kasirpintar.co.id/login");
    // await page.setDefaultNavigationTimeout(60000);

    // Mendapatkan elemen input untuk username dan password dan memasukkan nilai
    await page.type("input[name=email]", email);
    await page.type("input[name=password]", pass);

    // Klik tombol login
    await page.click("#login-form button");

    // await page.waitForNavigation();

    // page.waitForTimeout(10000);

    // Klik tombol login lagi soalnya dipentalno biasae
    // await page.type("input[name=password]", pass);
    // await page.click("#login-form button");

    // // Tunggu hingga proses login selesai (mungkin perlu menyesuaikan waktu sesuai dengan kebutuhan)
    // await page.waitForNavigation();

    // // Klik Menu Laporan
    // await page.goto("https://kasirpintar.co.id/account/laporan_staff_user");
    // await page.waitForNavigation();

    // // Mengaktifkan pemantauan request
    // await page.setRequestInterception(true);

    // // Mengatur handler untuk event response
    // page.on("response", async (response) => {
    //   // URL permintaan yang ingin Anda tangkap responsnya
    //   // https://kasirpintar.co.id/account/laporan_filter_ajax/Semua/Semua/Semua/2024-05-01/2024-05-31?struk=&_=1716171607147
    //   const targetUrlPrefix =
    //     "https://kasirpintar.co.id/account/laporan_filter_ajax/Semua/Semua/Semua";

    //   const responseUrl = response.url();

    //   // Periksa apakah URL respons dimulai dengan targetUrlPrefix
    //   if (responseUrl.startsWith(targetUrlPrefix)) {
    //     // Memodifikasi URL
    //     // const modifiedUrl = modifyUrl(requestUrl);
    //     console.log(`Original URL: ${requestUrl}`);
    //     // console.log(`Modified URL: ${modifiedUrl}`);
    //     // Melanjutkan permintaan dengan URL yang dimodifikasi
    //     // request.continue({ url: modifiedUrl });

    //     // // Tangkap isi respons
    //     // const responseBody = await response.text();
    //     // console.log(`Response body from ${targetUrlPrefix}:`, responseBody);

    //     // const jsonParse = JSON.parse(responseBody);

    //     // // Log the HTML content to the console
    //     // // console.log(htmlContent);
    //     // res.status(200).json(jsonParse);
    //     // await browser.close();
    //   }

    //   console.log(`Response URL: ${response.url()}`);
    //   console.log(`Response Status: ${response.status()}`);
    //   console.log(`Response OK: ${response.ok()}`);
    // });

    // await browser.close();
  } catch (error) {
    console.error("Error during scraping:", error);
    res.status(500).send("Internal Server Error");

    await browser.close();
  }
});

app.listen(port, "127.0.0.1", () => {
  console.log(`Server listening at http://127.0.0.1:${port}`);
});
