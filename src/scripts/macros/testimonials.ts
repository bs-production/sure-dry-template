function InitContentSelector(): void {
  const selector =
    document.querySelector<HTMLSelectElement>("#content_selector");

  const writtenContent =
    document.querySelector<HTMLDivElement>("#tabs-written");
  const videoContent = document.querySelector<HTMLDivElement>("#tabs-video");

  selector?.addEventListener("change", (_e) => {
    if (selector.value === "video") {
      writtenContent?.classList.add("hide");
      videoContent?.classList.remove("hide");
    }

    if (selector.value === "written") {
      writtenContent?.classList.remove("hide");
      videoContent?.classList.add("hide");
    }
  });
}

try {
  InitContentSelector();
} catch (e) {
  console.error(`Could not init Content Selector: ${e}`);
}
