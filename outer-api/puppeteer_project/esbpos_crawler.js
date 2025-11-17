// server.js
const express = require("express");
const puppeteer = require("puppeteer");

const app = express();
const port = 3001;

process.env.PUPPETEER_SKIP_CHROMIUM_DOWNLOAD = "true";

function formatDate(inputDateString) {
  const originalDate = new Date(inputDateString);
  const day = originalDate.getDate().toString().padStart(2, "0");
  const month = (originalDate.getMonth() + 1).toString().padStart(2, "0");
  const year = originalDate.getFullYear();
  return `${day}-${month}-${year}`;
}

app.get("/esbpos", async (req, res) => {
  const browser = await puppeteer.launch({
    headless: false,
    executablePath:
      "C:\\Users\\USER\\.cache\\puppeteer\\chrome\\win64-119.0.6045.105\\chrome-win64\\chrome.exe",
  });
  try {
    // Mendapatkan parameter dari baris perintah

    //C:\Users\USER\.cache\puppeteer\chrome\win64-119.0.6045.105\chrome-win64\chrome.exe

    const email = req.query.email;
    const pass = req.query.pass;
    const start_date = formatDate(req.query.start_date);
    const end_date = formatDate(req.query.end_date);
    const halaman = req.query.halaman;

    const page = await browser.newPage();

    // Mengakses DevTools Protocol
    const client = await page.target().createCDPSession();

    // Mengatur CacheDisabled menjadi false (menyala)
    await client.send("Network.setCacheDisabled", { cacheDisabled: false });

    await page.setRequestInterception(true);

    page.on("request", (req) => {
      if (
        /*req.resourceType() == "stylesheet" ||*/ req.resourceType() == "image"
      ) {
        req.abort();
      } else {
        // Ambil URL permintaan
        let url = req.url();
        // Cek apakah URL mengandung parameter perPage
        if (url.includes("perPage=")) {
          // Ganti nilai parameter perPage menjadi 10000000000
          url = url.replace(/perPage=\d+/, "perPage=10000000000");
          url = url.replace(/startDate=.+?&/, "startDate=" + start_date + "&");
          url = url.replace(/endDate=.+?&/, "endDate=" + end_date + "&");

          // Teruskan permintaan dengan URL yang sudah dimodifikasi
          req.continue({ url });
        } else {
          // Teruskan permintaan tanpa modifikasi
          req.continue();
        }
      }
    });

    await page.goto("https://poslite.esb.co.id/login");
    await page.setDefaultNavigationTimeout(60000);

    // Mendapatkan elemen input untuk username dan password dan memasukkan nilai
    await page.type("input[id=email]", email);
    await page.type("input[id=password]", pass);

    // Klik tombol login
    await page.click("button[type=submit]");

    await page.waitForNavigation();
    // Klik Menu Laporan
    await page.goto("https://poslite.esb.co.id/report/sales-detail/index");
    await page.setDefaultNavigationTimeout(60000);

    await page.waitForNavigation();
    await page.waitForSelector("button.btn-block.btn-primary");

    // Get the value of an input field
    const inputValue = await page.evaluate(() => {
      // Replace 'inputSelector' with the actual CSS selector of your input field
      const input = document.querySelector(".filter-calendar");
      return input ? input.value : null;
    });

    console.log("Current value of the input:", inputValue);

    // Set the value of an input field
    const newValue = start_date + " - " + end_date;

    await page.evaluate((value) => {
      // Use vanilla JavaScript to select the element and set its value
      document.querySelector(".filter-calendar").value = value;
    }, newValue);

    // Get the value of an input field
    const inputValue2 = await page.evaluate(() => {
      // Replace 'inputSelector' with the actual CSS selector of your input field
      const input = document.querySelector(".filter-calendar");
      return input ? input.value : null;
    });
    console.log("Value of the input after setting:", inputValue2);

    // Mengaktifkan pemantauan request
    await page.setRequestInterception(true);

    // Mengatur handler untuk event response
    page.on("response", async (response) => {
      // URL permintaan yang ingin Anda tangkap responsnya
      const targetUrlPrefix =
        "https://poslite.esb.co.id/api/web/v1/report/sales-detail?startDate=";

      const responseUrl = response.url();

      // Periksa apakah URL respons dimulai dengan targetUrlPrefix
      if (responseUrl.startsWith(targetUrlPrefix)) {
        // Tangkap isi respons
        const responseBody = await response.text();
        console.log(`Response body from ${targetUrlPrefix}:`, responseBody);

        const jsonParse = JSON.parse(responseBody);

        // Log the HTML content to the console
        // console.log(htmlContent);
        res.status(200).json(jsonParse);

        await browser.close();
      }

      console.log(`Response URL: ${response.url()}`);
      console.log(`Response Status: ${response.status()}`);
      console.log(`Response OK: ${response.ok()}`);
    });

    await page.waitForSelector("button.btn-block.btn-primary");
    await page.click("button.btn-block.btn-primary");
  } catch (error) {
    console.error("Error during scraping:", error);
    res.status(500).send("Internal Server Error");

    await browser.close();
  }
});

app.listen(port, "127.0.0.1", () => {
  console.log(`Server listening at http://127.0.0.1:${port}`);
});
