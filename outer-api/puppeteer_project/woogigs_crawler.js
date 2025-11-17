// server.js
const express = require("express");
const puppeteer = require("puppeteer");

const app = express();
const port = 3006;
// const port = 3010;

process.env.PUPPETEER_SKIP_CHROMIUM_DOWNLOAD = "true";

app.get("/woogigs", async (req, res) => {
  const browser = await puppeteer.launch({
    // headless: false,
    executablePath:
      "C:\\Users\\USER\\.cache\\puppeteer\\chrome\\win64-119.0.6045.105\\chrome-win64\\chrome.exe",
  });
  try {
    // Mendapatkan parameter dari baris perintah

    const businesscode = req.query.businesscode;
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

    page.on("request", (request) => {
      const targetUrlPrefix =
        "https://backoffice.woogigs.com/report-transaction/sales_complete";
      const requestUrl = request.url();
      if (request.resourceType() == "image") {
        request.abort();
      } else if (requestUrl.startsWith(targetUrlPrefix)) {
        let data = {
          method: request.method(),
          headers: request.headers(),
          postData: request.postData(),
        };

        // Modify the payload
        let postData = new URLSearchParams(data.postData);
        postData.set("date_start", start_date); // Modify date_start
        postData.set("date_end", end_date); // Modify date_end

        data.postData = postData.toString();

        request.continue(data);
      } else {
        request.continue();
      }
    });

    await page.goto("https://backoffice.woogigs.com/");
    await page.setDefaultNavigationTimeout(60000);

    // Mendapatkan elemen input untuk username dan password dan memasukkan nilai
    await page.type("input#txtBusinessCode", businesscode);
    await page.type("input#txtEmail", email);
    await page.type("input#txtPassword", pass);

    // Klik tombol login
    await page.click("button#btnLogin");

    await page.setDefaultNavigationTimeout(60000);
    // Tunggu hingga proses login selesai (mungkin perlu menyesuaikan waktu sesuai dengan kebutuhan)
    // Await page.waitForNavigation();

    // Get ID Outlet
    await page.waitForSelector("#menuReportSpecial", { visible: true });

    await page.goto("https://backoffice.woogigs.com/report/sales");

    // Mengatur handler untuk event response
    page.on("response", async (response) => {
      // URL permintaan yang ingin Anda tangkap responsnya
      const targetUrlPrefix =
        "https://backoffice.woogigs.com/report-transaction/sales_complete";

      const responseUrl = response.url();

      // Periksa apakah URL respons dimulai dengan targetUrlPrefix
      if (responseUrl.startsWith(targetUrlPrefix)) {
        // Tangkap isi respons
        const responseBody = await response.text();
        // console.log(`Response body from ${targetUrlPrefix}:`, responseBody);

        const jsonParse = JSON.parse(responseBody);
        console.log(jsonParse);

        // Log the HTML content to the console
        // console.log(htmlContent);
        res.header("Content-Type", "text/html");
        res.status(200).json(jsonParse);

        await browser.close();
      }
      console.log(`Response URL: ${response.url()}`);
      console.log(`Response Status: ${response.status()}`);
      console.log(`Response OK: ${response.ok()}`);
    });
  } catch (error) {
    console.error("Error during scraping:", error);
    res.status(500).send("Internal Server Error");

    await browser.close();
  }
});

app.listen(port, "127.0.0.1", () => {
  console.log(`Server listening at http://127.0.0.1:${port}`);
});
