function InitRelatedSelector(): void {
  const selector = document.querySelector<HTMLSelectElement>("#related_page");

  selector?.addEventListener("change", (_e) => {
    /**
     * NOTE:
     * =====
     * When the value is 0 it means
     * that its the current page (Or selected item)
     * or the default value ('Please select topic')
     */
    if (selector.value !== "0") {
      // Keep in the dev env
      window.location.href = selector.value + "?cache=0&dev_template=1&test=1";
    }
  });
}

InitRelatedSelector();
