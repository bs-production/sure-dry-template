/**
 * Questions answers
 * ==========
 * This is exactly like an accordion but its using
 * different class names and I don't know why
 *
 * NOTE:
 * ======
 * There seems to be conflict with this script and the one internally
 * used by the system
 */

interface IQA {
  container: HTMLUListElement;
  activeElement?: HTMLLIElement | null;
}

// Internal reference
let QAList: Array<IQA> = [];

function ToggleActiveQAItem(
  QAContainer: HTMLUListElement,
  QAItem: HTMLLIElement
) {
  for (let i = 0; i < QAList.length; i++) {
    if (QAContainer === QAList[i].container) {
      // remove style from current selected item If it exists
      if (QAList[i].activeElement) {
        QAList[i].activeElement.classList.remove("active");

        // If the same element is being clicked return
        if (QAList[i].activeElement === QAItem) {
          QAList[i].activeElement = null;
          break;
        }
      }

      // Set accordion item as active
      QAItem.classList.add("active");
      // Retain reference
      QAList[i].activeElement = QAItem;

      // Nothing else to do
      break;
    }
  }
}

function InitQA() {
  const accordianContainers: NodeListOf<HTMLUListElement> =
    document.querySelectorAll(".qa-wrap");

  accordianContainers.forEach((QAContainer, iCon) => {
    QAList.push({
      container: QAContainer,
      activeElement: null,
    });

    const QAItems: NodeListOf<HTMLLIElement> =
      QAContainer.querySelectorAll(".qa-item");

    QAItems.forEach((QAItem) => {
      // Account for initially open items
      if (QAItem.classList.contains("active")) {
        QAList[iCon].activeElement = QAItem;
      }

      const QATitle = QAItem.querySelector<HTMLDivElement>(".qa-header");

      // Only toggle on title click
      QATitle.onclick = (_e) => {
        ToggleActiveQAItem(QAContainer, QAItem);
      };
    });
  });
}

try {
  InitQA();
} catch (e) {
  console.error(`Could not init QA: ${e}`);
}
