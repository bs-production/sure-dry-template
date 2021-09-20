/**
 * Mobile silo navigation
 * ==================
 * I can't use the focus-within selector since it's
 * not supported ni Edge 15
 *
 */

function InitMobileSilo(): void {
  const SiloToggleMenuButton =
    document.querySelector<HTMLButtonElement>("#silo-menu-button");
  const SiloContainer =
    document.querySelector<HTMLDivElement>("#silo-container");

  SiloToggleMenuButton?.addEventListener("click", () => {
    SiloContainer.classList.toggle("show");
  });
}

try {
  InitMobileSilo();
} catch (e) {
  console.error(`Could not init Silo: ${e}`);
}
