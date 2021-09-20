import { zoomable } from "./zoom";

class Lightbox {
  lightboxLinks?: NodeListOf<HTMLAnchorElement>;

  constructor() {
    this.lightboxLinks = document.querySelectorAll(".lightbox");

    this._configure();
    this._run();
  }

  private _configure() {
    this.lightboxLinks.forEach((lightboxLink) => {
      this._setHDLink(lightboxLink);
      this._preventNavigation(lightboxLink);
    });
  }

  private _setHDLink(lightboxLink: HTMLAnchorElement) {
    const zoomableImage = lightboxLink.querySelector<HTMLImageElement>("img");
    const hdLink = lightboxLink.href;

    zoomableImage.setAttribute("data-zoom-src", hdLink);
  }

  private _preventNavigation(lightboxLink: HTMLAnchorElement) {
    lightboxLink?.addEventListener("click", (e) => e.preventDefault());
  }

  private _run() {
    zoomable(".lightbox img", {
      background: "rgba(0, 0, 0, 0.75)",
      margin: 32,
    });
  }
}

try {
  new Lightbox();
} catch (e) {
  console.error(`Could not init Lightbox: ${e}`);
}
