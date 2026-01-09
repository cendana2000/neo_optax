import type { NextFunction, Request, Response } from 'express';
import puppeteer from 'puppeteer';

export const scrappingData = async (req: Request, res: Response, next: NextFunction) => {
    let browser;

    try {
        browser = await puppeteer.launch({
            headless: true,
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox'
            ],
        });

        const email = req.query.email?.toString() || '';
        const pass = req.query.pass?.toString() || '';
        const start_date = req.query.start_date;
        const end_date = req.query.end_date;
        const halaman = req.query.halaman || 1;

        const page = await browser.newPage();
        page.setDefaultNavigationTimeout(60000);

        await page.setRequestInterception(true);
        page.on('request', (r) => {
            const blocked = ['image', 'stylesheet', 'font', 'media'];
            blocked.includes(r.resourceType()) ? r.abort() : r.continue();
        });

        await page.goto('https://dashboard.pawoon.com/login', {
            waitUntil: 'domcontentloaded',
        });

        await page.type('input[name=email]', email, { delay: 30 });
        await page.type('input[name=password]', pass, { delay: 30 });

        await Promise.all([
            page.click('#do-login'),
            page.waitForNavigation({ waitUntil: 'networkidle2' }),
        ]);

        const apiBaseUrl = 'https://dashboard.pawoon.com/report/sales-transaction/data';
        const fullUrl =
            `${apiBaseUrl}?page=${halaman}` +
            `&sort_by=desc` +
            `&start_date=${start_date}` +
            `&end_date=${end_date}` +
            `&status=success` +
            `&utc_offset=-7`;

        const response = await page.evaluate(async (url) => {
            const res = await fetch(url, { credentials: 'include' });
            return res.json();
        }, fullUrl);

        res.status(200).json(response);
    } catch (error) {
        console.error('Error during scraping:', error);
        res.status(500).send('Internal Server Error');
    } finally {
        if (browser) await browser.close();
    }
}