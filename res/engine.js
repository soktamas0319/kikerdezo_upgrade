// First Level Items MouseOver Style
function chgPageStl(OverStyle, divID) {
	if (OverStyle == 1) {
		document.all["ID_elpage" + divID].style.backgroundColor = mainOverCol;
		document.all["ID_elpage" + divID].style.color = mainFntOverCol;
	}
	else {
		document.all["ID_elpage" + divID].style.backgroundColor = mainBackCol;
		document.all["ID_elpage" + divID].style.color = mainFntCol;
	}
}
