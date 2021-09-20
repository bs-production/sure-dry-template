/**
 * Accordion
 * ==========
 * All pages usually only contain one accordion but
 * I'm making it work for the small chance that it might
 * contain multiple
 */

interface IAccordion {
  container: HTMLUListElement;
  activeElement?: HTMLLIElement | null;
}

// Internal reference
let AccordionList: Array<IAccordion> = [];

function ToggleActiveAccordionItem(
  accordionContainer: HTMLUListElement,
  accordionItem: HTMLLIElement
) {
  for (let i = 0; i < AccordionList.length; i++) {
    if (accordionContainer === AccordionList[i].container) {
      // remove style from current selected item If it exists
      if (AccordionList[i].activeElement) {
        AccordionList[i].activeElement.classList.remove("active");

        // If the same element is being clicked return
        if (AccordionList[i].activeElement === accordionItem) {
          AccordionList[i].activeElement = null;
          break;
        }
      }

      // Set accordion item as active
      accordionItem.classList.add("active");
      // Retain reference
      AccordionList[i].activeElement = accordionItem;

      // Nothing else to do
      break;
    }
  }
}

function InitAccordion() {
  const accordianContainers: NodeListOf<HTMLUListElement> =
    document.querySelectorAll(".accordion");

  accordianContainers.forEach((accordionContainer, iCon) => {
    AccordionList.push({
      container: accordionContainer,
      activeElement: null,
    });

    const accordionItems: NodeListOf<HTMLLIElement> =
      accordionContainer.querySelectorAll(".accordion-navigation");

    accordionItems.forEach((accordionItem) => {
      // Account for initially open items
      if (accordionItem.classList.contains("active")) {
        AccordionList[iCon].activeElement = accordionItem;
      }

      const accordionItemTitle = accordionItem.querySelector("a");

      // Only toggle on title click
      accordionItemTitle.onclick = (e) => {
        e.preventDefault();
        ToggleActiveAccordionItem(accordionContainer, accordionItem);
      };
    });
  });
}

try {
  InitAccordion();
} catch (e) {
  console.error(`Could not init Accordion: ${e}`);
}
