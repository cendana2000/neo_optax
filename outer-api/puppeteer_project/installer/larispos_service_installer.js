var Service = require("node-windows").Service;

// Create a new service object
var svc = new Service({
  name: "Larispos Crawler Service",
  description: "Larispos Service ETAX Crawler.",
  script:
    "C:\\xampp\\htdocs\\crawler_etax\\puppeteer_project\\larispos_crawler.js",
  nodeOptions: ["--harmony", "--max_old_space_size=4096"],
});

// Ambil argumen yang relevan (indeks 2 dan seterusnya)
const arguments = process.argv.slice(2);

// Cek apakah terdapat argumen "uninstall"
if (arguments.includes("uninstall")) {
  // Lakukan uninstall
  // Listen for the "uninstall" event so we know when it's done.
  svc.on("uninstall", function () {
    console.log("Uninstall complete.");
    console.log("The service exists: ", svc.exists);
  });

  // Uninstall the service.
  svc.uninstall();
  console.log("Service uninstalled successfully.");
} else {
  // Lakukan install
  // Listen for the "install" event, which indicates the
  // Process is available as a service.
  svc.on("install", function () {
    svc.start();
  });

  svc.install();
  console.log("Service installed successfully.");
}
