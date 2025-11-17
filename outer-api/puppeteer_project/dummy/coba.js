const puppeteer = require("puppeteer");

(async () => {
  try {
    const browser = await puppeteer.launch({ headless: false });
    const page = await browser.newPage();

    // Melakukan navigasi ke halaman
    await page.goto("https://example.com");

    // Mendapatkan isi halaman
    const pageContent = await page.content();

    // Menampilkan isi halaman di console
    console.log(pageContent);
  } catch (error) {
    console.error("Puppeteer error:", error.message);
  }
})();
