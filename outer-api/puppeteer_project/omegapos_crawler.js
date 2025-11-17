const express = require("express");
const puppeteer = require("puppeteer");

const app = express();
const port = 3007;

process.env.PUPPETEER_SKIP_CHROMIUM_DOWNLOAD = "true";

function formatDate(inputDateString) {
  const originalDate = new Date(inputDateString);
  const day = originalDate.getDate().toString().padStart(2, "0");
  const month = (originalDate.getMonth() + 1).toString().padStart(2, "0");
  const year = originalDate.getFullYear();
  return `${year}-${month}-${day}`;
}

function delay(time) {
  return new Promise(function (resolve) {
    setTimeout(resolve, time);
  });
}

app.get("/omega", async (req, res) => {
  const browser = await puppeteer.launch({
    // headless: false,
    executablePath:
      "C:\\Users\\USER\\.cache\\puppeteer\\chrome\\win64-119.0.6045.105\\chrome-win64\\chrome.exe",
  });

  try {
    const username = req.query.username;
    const password = req.query.password;
    const start_date = formatDate(req.query.start_date);
    const end_date = formatDate(req.query.end_date);

    const targetUrl = "https://omegasoft.co.id/dashboard/login/getdata";

    const page = await browser.newPage();

    await page.setRequestInterception(true);

    page.on("request", (req) => {
      if (req.resourceType() === "image") {
        req.abort();
      } else if (req.url().startsWith(targetUrl) && req.method() === "POST") {
        let postData = req.postData();

        // Modifikasi nilai 'tglawal' dan 'tglakhir' dalam postData
        postData = postData.replace(/(tglawal=)[^&]*/, `$1${start_date}`);
        postData = postData.replace(/(tglakhir=)[^&]*/, `$1${end_date}`);

        // Teruskan request dengan postData yang sudah dimodifikasi
        req.continue({
          headers: req.headers(),
          postData,
        });
      } else {
        req.continue();
      }
    });

    await page.goto("https://omegasoft.co.id/dashboard/web/home");
    await page.setDefaultNavigationTimeout(60000);

    // Tunggu sampai elemen input username dan password tersedia
    await page.waitForSelector("#username", { visible: true });
    await page.waitForSelector("#password", { visible: true });

    // Isi form dengan username dan password
    await page.type("#username", username);
    await page.type("#password", password);

    // Tunggu sampai tombol login tersedia dan bisa diklik
    await page.waitForSelector("#btn", { visible: true });

    // Safely attempt to click the pagination link
    const btnLoginExists = await page.evaluate(() => {
      const btnLogin = document.querySelector(`#btn`);
      if (btnLogin) {
        btnLogin.click();
        return true;
      }
      return false;
    });

    console.log("Login berhasil, halaman berhasil dimuat.");

    const scrapePage = async () => {
      await page.waitForSelector("#btn_search");

      await page.click("#btn_search");
      console.log("#btn_search clicked successfully.");

      // Reload the page with updated query params or form data
      const response = await page.waitForResponse(
        (res) => res.url().startsWith(targetUrl),
        { timeout: 60000 }
      );
      const responseBody = await response.text();

      console.log(`Response body from ${targetUrl}:`, responseBody.length);

      const jsonParse = JSON.parse(responseBody);

      res.status(200).json(jsonParse);
      await browser.close();
    };

    // Start scraping from the first page
    await scrapePage();
  } catch (error) {
    console.error("Error during scraping:", error);
    res.status(500).send("Internal Server Error");
    await browser.close();
  }
});

app.listen(port, "0.0.0.0", () => {
  console.log(`Server listening at http://0.0.0.0:${port}`);
});
