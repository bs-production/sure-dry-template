import * as dat from "dat.gui";

// Plugins
import { NavigationPlugin } from "./plugins/navigation.plugin";

class DevTools {
  GUI = new dat.GUI();

  constructor() {
    // Styles fixes
    this._styleTweaks();

    // Run PLugins
    new NavigationPlugin(this.GUI);
  }

  private _styleTweaks() {
    const style = document.createElement("style");
    const styles = [
      /**
       * PARENT STYLES
       * ==============
       * Push parent to top
       */
      `
        .dg.ac {
          z-index: 9999999999
        }
      `,

      /**
       * FIX INPUTS:
       * ===============
       */
      `
        .dg.ac input[type="text"] {
          line-height: 1.5;
          margin-top: 2px !important;
        }
      `,

      /**
       * DISABLE BUTTON STYLES
       */
      `
        .dg.ac .button {
          display:none !important;
        }
      `,
    ];

    style.innerHTML = styles.join("");
    document.getElementsByTagName("head")[0].appendChild(style);

    this.GUI.width = 300;
  }

  // TODO: Implement some kind of storage machanism
  private _createStorage() {}
}

try {
  // new DevTools();
} catch (e) {
  console.error(`Could not init Devtools: ${e}`);
}
