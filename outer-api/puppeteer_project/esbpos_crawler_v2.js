// server.js
const express = require("express");
const puppeteer = require("puppeteer");

const app = express();
const port = 3008;

process.env.PUPPETEER_SKIP_CHROMIUM_DOWNLOAD = "true";

function formatDate(inputDateString) {
  const originalDate = new Date(inputDateString);
  const day = originalDate.getDate().toString().padStart(2, "0");
  const month = (originalDate.getMonth() + 1).toString().padStart(2, "0");
  const year = originalDate.getFullYear();
  return `${day}-${month}-${year}`;
}

function delay(time) {
  return new Promise(function (resolve) {
    setTimeout(resolve, time);
  });
}

app.get("/esbpos", async (req, res) => {
  const browser = await puppeteer.launch({
    headless: false,
    executablePath:
      "C:\\Users\\USER\\.cache\\puppeteer\\chrome\\win64-119.0.6045.105\\chrome-win64\\chrome.exe",
  });
  try {
    // Mendapatkan parameter dari baris perintah
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

    const reportDate = start_date + " - " + end_date;
    const dateFrom = start_date;
    const dateTo = end_date;

    page.on("request", (req) => {
      if (req.resourceType() == "image") {
        req.abort();
      } else {
        const targetUrl = "https://erp.esb.co.id/report/report-sales";

        // Cek apakah URL sesuai dengan target
        if (req.url().startsWith(targetUrl) && req.method() === "POST") {
          let postData = req.postData();

          // Ganti nilai-nilai form data
          postData = postData.replace(
            /(SalesReport\[reportDate\]=)[^&]*/,
            `$1${reportDate}`
          );
          postData = postData.replace(
            /(SalesReport\[dateFrom\]=)[^&]*/,
            `$1${dateFrom}`
          );
          postData = postData.replace(
            /(SalesReport\[dateTo\]=)[^&]*/,
            `$1${dateTo}`
          );

          // Teruskan request dengan payload yang sudah dimodifikasi
          req.continue({
            headers: req.headers(),
            postData,
          });
        } else {
          // Jika bukan request target, lanjutkan tanpa perubahan
          req.continue();
        }
      }
    });

    await page.goto("https://erp.esb.co.id/site/login");
    await page.setDefaultNavigationTimeout(60000);

    // Mendapatkan elemen input untuk username dan password dan memasukkan nilai
    console.log(email);
    console.log(pass);
    await page.type("input[id=loginform-username]", email);
    await page.type("input[id=loginform-password]", pass);

    // Klik tombol login
    await page.click("button[type=submit]");

    await page.waitForSelector(".swal2-confirm");

    await page.click(".swal2-confirm");

    await page.waitForNavigation();
    // Klik Menu Laporan
    await page.goto("https://erp.esb.co.id/report/report-sales");
    await page.setDefaultNavigationTimeout(60000);

    console.log("waitForSelector : #salesreport-reportdate");
    await page.waitForSelector("#salesreport-reportdate");
    console.log("#salesreport-reportdate founded");

    // Set the value of an input field
    const newValue = start_date + " - " + end_date;

    await delay(3000);

    await page.evaluate((value) => {
      // Use vanilla JavaScript to select the element and set its value
      document.querySelector("#salesreport-reportdate").value = value;
    }, newValue);

    console.log("newValue : ", newValue);
    await page.click("#showReport");
    console.log("#showReport was clicked");

    let currentPage = 0;
    let lastPageNumber = 0;
    let data_content = "";
    let trial = 0;

    const scrapePage = async (pageNum, isStarter, isFirst) => {
      trial++;

      await page.waitForSelector("#grid-custom-report-container");

      // Reload the page with updated query params or form data
      const response = await page.waitForResponse(
        (res) =>
          res.url().startsWith("https://erp.esb.co.id/report/report-sales"),
        { timeout: 60000 }
      );
      const responseBody = await response.text();

      // Extract the last page number if it hasn't been set
      if (/*trial == 2 ||*/ isStarter) {
        const lastPageMatch = responseBody.match(
          /<li class="last">.*?data-page="(\d+)"/
        );
        lastPageNumber = lastPageMatch ? parseInt(lastPageMatch[1]) : 0;
        console.log("Last page number:", lastPageNumber);
      }

      if (lastPageNumber == 0) {
        console.log(`No data found.. end scrapping`);
        res.send("null");
        await browser.close();
      }

      // Extract data from current page and accumulate
      const data_html = responseBody.match(
        /<div id="grid-custom-report-container".*?<tbody>(.+?)<\/tbody>/s
      );

      if (!isStarter) {
        if (!isFirst) {
          data_content += data_html ? data_html[1] : "";
        } else {
          data_content = (data_html ? data_html[1] : "") + data_content;
        }
        //console.log("crawled row :", data_content);
      }

      // Check if there are more pages to scrape
      console.log(
        "pagenum < lastpagenumber",
        pageNum,
        lastPageNumber,
        currentPage,
        trial
      );
      if (pageNum < lastPageNumber) {
        if (!isStarter) {
          currentPage++;
        } else {
          currentPage = 0;
        }

        // Safely attempt to click the pagination link
        const nextPageExists = await page.evaluate((nextPage) => {
          const nextPageLink = document.querySelector(
            `a[data-page="${nextPage}"]`
          );
          if (nextPageLink) {
            nextPageLink.click();
            return true;
          }
          return false;
        }, currentPage);

        if (isFirst) {
          console.log(`Back to page 1 end scrapping.`);
          //res.send(data_content);
          await browser.close();
        } else {
          if (nextPageExists) {
            await scrapePage(currentPage, false, false); // Recursive call for the next page
          } else {
            console.log(
              `Could not find link for page ${currentPage}. Back to link number 1.`
            );
            currentPage = -1;
            await scrapePage(-1, false, true); // Call for the first page because firstpage is unclickable
          }
        }
      } else {
        console.log("Finished scraping all pages");
        //res.send(data_content);
        await browser.close();
      }
    };

    // Start scraping from the first page
    await scrapePage(currentPage, true, false);
  } catch (error) {
    console.error("Error during scraping:", error);
    //res.status(500).send("Internal Server Error");

    await browser.close();
  }
});

app.listen(port, "127.0.0.1", () => {
  console.log(`Server listening at http://127.0.0.1:${port}`);
});
