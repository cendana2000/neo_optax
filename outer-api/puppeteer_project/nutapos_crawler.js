// server.js
const express = require("express");
const puppeteer = require("puppeteer");

const app = express();
const port = 3002;
// const port = 3010;

process.env.PUPPETEER_SKIP_CHROMIUM_DOWNLOAD = "true";

function delay(time) {
  return new Promise(function (resolve) {
    setTimeout(resolve, time);
  });
}

app.get("/nutapos", async (req, res) => {
  const browser = await puppeteer.launch({
    // headless: false,
    executablePath:
      "C:\\Users\\USER\\.cache\\puppeteer\\chrome\\win64-119.0.6045.105\\chrome-win64\\chrome.exe",
  });
  try {
    // Mendapatkan parameter dari baris perintah

    const namausaha = req.query.namausaha;
    const username = req.query.username;
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
      if (req.resourceType() == "stylesheet" || req.resourceType() == "image") {
        req.abort();
      } else {
        req.continue();
      }
    });

    await page.goto("https://www.nutacloud.com/authentication/loginv2");
    await page.setDefaultNavigationTimeout(60000);

    // Mendapatkan elemen input untuk username dan password dan memasukkan nilai
    await page.type("input[name=idperusahaan]", namausaha);
    await page.type("input[name=username]", username);
    await page.type("input[name=password]", pass);

    // Klik tombol login
    await page.click("button[type=submit]");

    await page.setDefaultNavigationTimeout(60000);
    // Tunggu hingga proses login selesai (mungkin perlu menyesuaikan waktu sesuai dengan kebutuhan)
    // Await page.waitForNavigation();

    await delay(1000);

    await page.goto("https://www.nutacloud.com/laporan/penjualan");
    await page.setDefaultNavigationTimeout(60000);

    // Get ID Outlet
    await page.waitForSelector("#outlet > option");
    // #outlet > option

    // Get the value of an input field
    const id_outlet = await page.evaluate(() => {
      // Replace 'inputSelector' with the actual CSS selector of your input field
      const input = document.querySelector("#outlet > option");
      return input ? input.value : null;
    });

    console.log(id_outlet);

    // Mengatur handler untuk event response
    page.on("response", async (response) => {
      // URL permintaan yang ingin Anda tangkap responsnya
      const targetUrlPrefix =
        "https://www.nutacloud.com/laporan/penjualandom?outlet";

      const responseUrl = response.url();

      // Periksa apakah URL respons dimulai dengan targetUrlPrefix
      if (responseUrl.startsWith(targetUrlPrefix)) {
        // Tangkap isi respons
        const responseBody = await response.text();
        // console.log(`Response body from ${targetUrlPrefix}:`, responseBody);

        const jsonParse = JSON.parse(responseBody);

        // Log the HTML content to the console
        // console.log(htmlContent);
        res.header("Content-Type", "text/html");
        res.status(200).send("<table>" + jsonParse.grid_html + "<table>");

        await browser.close();
      }

      //   console.log(`Response URL: ${response.url()}`);
      //   console.log(`Response Status: ${response.status()}`);
      //   console.log(`Response OK: ${response.ok()}`);
    });

    await page.goto(
      "https://www.nutacloud.com/laporan/penjualandom?outlet=" +
        id_outlet +
        "&date_start=" +
        start_date +
        "&date_end=" +
        end_date
    );
  } catch (error) {
    console.error("Error during scraping:", error);
    res.status(500).send("Internal Server Error");

    await browser.close();
  }
});

app.listen(port, "127.0.0.1", () => {
  console.log(`Server listening at http://127.0.0.1:${port}`);
});
