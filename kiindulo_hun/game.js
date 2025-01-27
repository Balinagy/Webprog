const descriptionPopup = document.querySelector("#description-popup");
const descriptionButton = document.querySelector("#description-button");
const closeButton = document.querySelector(".close");
const easyDifficultyButton = document.querySelector("#easy-button");
const hardDifficultyButton = document.querySelector("#hard-button");
const nameInput = document.querySelector("#name-input");
const startButton = document.querySelector("#start-button");
const menu = document.querySelector("#menu");
const gameArea = document.querySelector("#game-area");
const nameField = document.querySelector("#name-field");
const timeField = document.querySelector("#time-field");
const errorMessage = document.querySelector("#error-message");
const table = document.querySelector("#game-tiles");
const winningTimeEl = document.querySelector("#winning-time");
const restartButton = document.querySelector("#restart-game")
const winningPopup = document.querySelector("#winning-popup");
const winningName = document.querySelector("#winning-name");

//leírás popup megjelenítése és eltűntetése
descriptionButton.addEventListener("click", () => {
    descriptionPopup.style.display = "block";
});

closeButton.addEventListener("click", () => {
    descriptionPopup.style.display = "none";
});

//nehézség gomb aktuálisan kiválasztott osztálylistájának változtatása
easyDifficultyButton.addEventListener("click", () =>{
    easyDifficultyButton.classList.remove("notSelectedButton");
    easyDifficultyButton.classList.add("SelectedButton");
    hardDifficultyButton.classList.remove("SelectedButton");
    hardDifficultyButton.classList.add("notSelectedButton");
    startButtonUpdater();
})

hardDifficultyButton.addEventListener("click", () => {
    hardDifficultyButton.classList.remove("notSelectedButton");
    hardDifficultyButton.classList.add("SelectedButton");
    easyDifficultyButton.classList.remove("SelectedButton");
    easyDifficultyButton.classList.add("notSelectedButton");
    startButtonUpdater();
});

nameInput.addEventListener("keydown", () =>{
    startButtonUpdater();
})

//delegate segédfunction
function delegate(parent, type, selector, handler) {
    parent.addEventListener(type, function (event) {
        const targetElement = event.target.closest(selector);

        if (this.contains(targetElement)) {
            handler.call(targetElement, event);
        }
    });
}

//ez a function csak megváltoztatja a start button színét, ha kattintható
function startButtonUpdater()
{
    if((hardDifficultyButton.classList.contains("SelectedButton")||easyDifficultyButton.classList.contains("SelectedButton"))
    && nameInput.value.trim() !== "")
    {
        startButton.style.backgroundColor = "#A4A5AB";
    }
    else{
        startButton.style.backgroundColor = "#a4a5ab6c";
    }
}

//itt indítom el a játékot, a beállításoktól függően, valamint feldobok egy error üzenetet, ha valami hiányzik
startButton.addEventListener("click", ()=>
{
    errorMessage.style.display = "none";
    if(!hardDifficultyButton.classList.contains("SelectedButton") && !easyDifficultyButton.classList.contains("SelectedButton")
    && !(nameInput.value.trim() !== ""))
    {
        errorMessage.textContent = "Írd be az adataid!";
        errorMessage.style.display = "flex";
    }
    else if(!(hardDifficultyButton.classList.contains("SelectedButton")||easyDifficultyButton.classList.contains("SelectedButton")))
    {
        errorMessage.textContent = "Válaszd ki a játék nehézségét!";
        errorMessage.style.display = "flex";
    }
    else if(nameInput.value.trim() === "")
    {
        errorMessage.textContent = "Add meg a nevedet!";
        errorMessage.style.display = "flex";
    }
    else
    {
        playGame();
    }
})

function playGame()
{
    //random számot itt generálom
    const randomMapNumber = Math.floor(Math.random() * 5);
    //könnyű játék esetén 0-val, nehéz esetén 1-el hívom meg az initializegame-t
    if(easyDifficultyButton.classList.contains("SelectedButton"))
    {
        initializeGame(0,randomMapNumber);
    }
    else
    {
        initializeGame(1,randomMapNumber);
    }
}

//itt változtatom meg a mezőket kattintásra
delegate(table, 'click', 'td.cell', (e) => {
    switch (e.target.style.backgroundImage.toString()) {
        case 'url("pics/tiles/bHor.png")':
            e.target.style.backgroundImage = "url('pics/tiles/railbHor.png')";
            break;
        case 'url("pics/tiles/bVer.png")':
            e.target.style.backgroundImage = "url('pics/tiles/railbVer.png')";
            break;
        case 'url("pics/tiles/mTR.png")':
            e.target.style.backgroundImage = "url('pics/tiles/railmTR.png')";
            break;
        case 'url("pics/tiles/mTL.png")':
            e.target.style.backgroundImage = "url('pics/tiles/railmTL.png')";
            break;
        case 'url("pics/tiles/mBR.png")':
            e.target.style.backgroundImage = "url('pics/tiles/railmBR.png')";
            break;
        case 'url("pics/tiles/mBL.png")':
            e.target.style.backgroundImage = "url('pics/tiles/railmBL.png')";
            break;
        case 'url("pics/tiles/f.png")':
            e.target.style.backgroundImage = "url('pics/tiles/railfHor.png')";
            break;
        case 'url("pics/tiles/railfHor.png")':
            e.target.style.backgroundImage = "url('pics/tiles/railfVer.png')";
            break;
        case 'url("pics/tiles/railfVer.png")':
            e.target.style.backgroundImage = "url('pics/tiles/railfBR.png')";
            break;
        case 'url("pics/tiles/railfBR.png")':
            e.target.style.backgroundImage = "url('pics/tiles/railfBL.png')";
            break;
        case 'url("pics/tiles/railfBL.png")':
            e.target.style.backgroundImage = "url('pics/tiles/railfTL.png')";
            break;
        case 'url("pics/tiles/railfTL.png")':
            e.target.style.backgroundImage = "url('pics/tiles/railfTR.png')";
            break;
        case 'url("pics/tiles/railfTR.png")':
            e.target.style.backgroundImage = "url('pics/tiles/railfHor.png')";
            break;
        default:
            break;
    }
})


//megnézi, hogy nyertes-e a játék
function checkIfWon()
{
    //segédmátrix létrehozása
    const helperMatrix = [];
    for(let i = 0; i < table.rows.length; i++)
    {
        const helperMatrixRow = [];
        for(let j = 0; j < table.rows.length; j++)
        {
            helperMatrixRow.push(true);
        }
        helperMatrix.push(helperMatrixRow);
    }

    //kezdeti cella megkeresése (mivel minden játéktábla első sorában van olyan mező, amelyikre le lehet rakni sínt, így 
    //elég csak az első sort vizsgálni)
    const firstTableRow = table.rows[0];
    let firstRowHelper = 0;
    while (firstRowHelper < firstTableRow.cells.length && 
    firstTableRow.cells[firstRowHelper].style.backgroundImage === 'url("pics/tiles/o.png")') 
    {
        firstRowHelper += 1;
    }
    //követem, hogy melyik cellán kezdett, és mi a jelenlegi cella és az irányt is
    const starterCell = [0,firstRowHelper];
    let currentCell = [0,firstRowHelper];
    //kezdeti cella false a segédmátrixban
    helperMatrix[currentCell[0]][currentCell[1]] = false;
    let nextDir = "";
    //kezdeti irány meghatározása és az aktuális cella elmozgatása
    switch(table.rows[currentCell[0]].cells[currentCell[1]].style.backgroundImage){
        case 'url("pics/tiles/railbHor.png")':
            nextDir = "right";
            if(currentCell[1]+1 === table.rows.length)
            {
                return false;
            }
            currentCell[1] += 1;
            break;

        case 'url("pics/tiles/railbVer.png")':
            nextDir = "up";
            if(currentCell[0]-1 < 0)
            {
                return false;
            }
            currentCell[0] -= 1;
            break;

        case 'url("pics/tiles/railfVer.png")':
            nextDir = "up";
            if(currentCell[0]-1 < 0)
            {
                return false;
            }
            currentCell[0] -= 1;
            break;

        case 'url("pics/tiles/railfHor.png")':
            nextDir = "right";
            if(currentCell[1]+1 === table.rows.length)
            {
                return false;
            }
            currentCell[1] += 1;
            break;

            break;
        case 'url("pics/tiles/railfBR.png")':
            nextDir = "down";
            if(currentCell[0]+1 === table.rows.length)
            {
                return false;
            }
            currentCell[0] += 1;
            break;

            break;
        case 'url("pics/tiles/railfBL.png")':
            nextDir = "down";
            if(currentCell[0]+1 === table.rows.length)
            {
                return false;
            }
            currentCell[0] += 1;
            break;

        case 'url("pics/tiles/railfTL.png")':
            nextDir = "up";
            if(currentCell[0]-1 < 0)
            {
                return false;
            }
            currentCell[0] -= 1;
            break;

        case 'url("pics/tiles/railfTR.png")':
            nextDir = "up";
            if(currentCell[0]-1 < 0)
            {
                return false;
            }
            currentCell[0] -= 1;
            break;
        case 'url("pics/tiles/railmBR.png")':
            nextDir = "down";
            if(currentCell[0]+1 === table.rows.length)
            {
                return false;
            }
            currentCell[0] += 1;
            break;

        case 'url("pics/tiles/railmBL.png")':
            nextDir = "down";
            if(currentCell[0]+1 === table.rows.length)
            {
                return false;
            }
            currentCell[0] += 1;
            break;

        case 'url("pics/tiles/railmTL.png")':
            nextDir = "up";
            if(currentCell[0]-1 < 0)
            {
                return false;
            }
            currentCell[0] -= 1;
            break;

        case 'url("pics/tiles/railmTR.png")':
            nextDir = "up";
            if(currentCell[0]-1 < 0)
            {
                return false;
            }
            currentCell[0] -= 1;
            break;

        default:
            console.log("Big problem :(");
            return false;
            break;
    }
    //kezdeti irány meghatározva cella elmozgatva
    //előző mozgatás függvényében vizsgáljuk az aktuális cellát
    //addig megy a ciklus, ameddig az aktuális cellával el nem érjuk a kezdeti cellát tehát nincs egy körünk
    while(currentCell[0] != starterCell[0] || currentCell[1] != starterCell[1])
    {
        //itt kiválasztom az éppen vizsgált cellát, ami az actualcellben lesz
        //nem összekeverendő a currrentCellel!!!!!!
        const actualRow = table.rows[currentCell[0]];
        const actualCell = actualRow.cells[currentCell[1]];
        if(nextDir === "up")
        {
            switch(actualCell.style.backgroundImage){
                case 'url("pics/tiles/railbVer.png")':
                    nextDir = "up";
                    if(currentCell[0] === 0)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[0] -= 1;
                break;

                case 'url("pics/tiles/railfVer.png")':
                    nextDir = "up";
                    if(currentCell[0] === 0)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[0] -= 1;
                break;

                case 'url("pics/tiles/railfBR.png")':
                    nextDir = "right";
                    if(currentCell[1] === table.rows.length-1)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[1] += 1;
                break;

                case 'url("pics/tiles/railfBL.png")':
                    nextDir = "left";
                    if(currentCell[1] === 0)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[1] -= 1;
                break;

                case 'url("pics/tiles/railmBR.png")':
                    nextDir = "right";
                    if(currentCell[1] === table.rows.length-1)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[1] += 1;
                break;

                case 'url("pics/tiles/railmBL.png")':
                    nextDir = "left";
                    if(currentCell[1] === 0)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[1] -= 1;
                break;

                default:
                    return false;
                break;
            }
        }
        else if(nextDir === "down")
        {    
            switch(actualCell.style.backgroundImage){
                case 'url("pics/tiles/railbVer.png")':
                    nextDir = "down";
                    if(currentCell[0] === table.rows.length-1)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[0] += 1;
                break;

                case 'url("pics/tiles/railfVer.png")':
                    nextDir = "down";
                    if(currentCell[0] === table.rows.length-1)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[0] += 1;
                break;

                case 'url("pics/tiles/railfTL.png")':
                    nextDir = "left";
                    if(currentCell[1] === 0)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[1] -= 1;
                break;

                case 'url("pics/tiles/railfTR.png")':
                    nextDir = "right";
                    if(currentCell[1] === table.rows.length-1)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[1] += 1;
                break;

                case 'url("pics/tiles/railmTL.png")':
                    nextDir = "left";
                    if(currentCell[1] === 0)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[1] -= 1;
                break;

                case 'url("pics/tiles/railmTR.png")':
                    nextDir = "right";
                    if(currentCell[1] === table.rows.length-1)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[1] += 1;
                break;

                default:
                    return false;
                break;
            }
        }
        else if(nextDir === "left")
        {    
            switch(actualCell.style.backgroundImage){
                case 'url("pics/tiles/railbHor.png")':
                    nextDir = "left";
                    if(currentCell[1] === 0)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[1] -= 1;
                break;

                case 'url("pics/tiles/railfHor.png")':
                    nextDir = "left";
                    if(currentCell[1] === 0)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[1] -= 1;
                break;

                case 'url("pics/tiles/railfBR.png")':
                    nextDir = "down";
                    if(currentCell[0] === table.rows.length-1)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[0] += 1;
                break;

                case 'url("pics/tiles/railfTR.png")':
                    nextDir = "up";
                    if(currentCell[0] === 0)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[0] -= 1;
                break;

                case 'url("pics/tiles/railmBR.png")':
                    nextDir = "down";
                    if(currentCell[0] === table.rows.length-1)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[0] += 1;
                break;

                case 'url("pics/tiles/railmTR.png")':
                    nextDir = "up";
                    if(currentCell[0] === 0)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[0] -= 1;
                break;

                default:
                    return false;
                break;
            }
        }
        else if(nextDir === "right")
        {    
            switch(actualCell.style.backgroundImage){
                case 'url("pics/tiles/railbHor.png")':
                    nextDir = "right";
                    if(currentCell[1] === table.rows.length-1)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[1] += 1;
                break;

                case 'url("pics/tiles/railfHor.png")':
                    nextDir = "right";
                    if(currentCell[1] === table.rows.length-1)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[1] += 1;
                break;

                case 'url("pics/tiles/railfBL.png")':
                    nextDir = "down";
                    if(currentCell[0] === table.rows.length-1)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[0] += 1;
                break;

                case 'url("pics/tiles/railfTL.png")':
                    nextDir = "up";
                    if(currentCell[0] === 0)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[0] -= 1;
                break;

                case 'url("pics/tiles/railmBL.png")':
                    nextDir = "down";
                    if(currentCell[0] === table.rows.length-1)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[0] += 1;
                break;

                case 'url("pics/tiles/railmTL.png")':
                    nextDir = "up";
                    if(currentCell[0] === 0)
                    {
                        return false;
                    }
                    helperMatrix[currentCell[0]][currentCell[1]] = false;
                    currentCell[0] -= 1;
                break;

                default:
                    return false;
                break;
            }
        }
        else
        {
            return false; //ide sosem futhat bele elméletben, de a biztonság kedvéért
        }
    }
    //while loopnak itt a vége, megnéztem van-e kör, viszont most kell csekkolni, csak oasis van-e mindenhol amelyik cellában nem voltunk
    for(let i = 0; i< helperMatrix[0].length;i++)
    {
        for(let j = 0; j< helperMatrix[0].length;j++)
        {
            if(helperMatrix[i][j])
            {
                const oasisCheckerRow = table.rows[i];
                const oasisChecker = oasisCheckerRow.cells[j];
                if(oasisChecker.style.backgroundImage != 'url("pics/tiles/o.png")')
                {
                    return false;
                }
            }
        }
    }
    //csak a végén dob vissza egy true-t
    return true;
    //működik!!! nem hiszem el
}

let intervalId;//itt deklarálom, hogy lássák a függvények

restartButton.addEventListener("click", () => {
    winningPopup.style.display = "none";
    //újratölti az oldalt
    location.reload();
});

//minden tábla kattintás után megnézi, hogy nyertes játék-e
table.addEventListener("click", ()=>{
    if(checkIfWon())
    {
        clearInterval(intervalId); // Időzítő leállítása
        winningName.textContent = `Szép munka ${nameField.textContent}!`;
        winningPopup.style.display = "block";
        const winTime = timeField.textContent;
        winningTimeEl.textContent = `Időd: ${winTime}`;
        winningPopup.style.display = "block";
        saveScore(HelperMapname,nameField.textContent,winTime);
        displayLeaderboard(HelperMapname);
    }
    });
//ez a toplistához kell, hogy lássa a másik függvény is
let HelperMapname;
function initializeGame(diff,randomMapNumber)
{
    menu.style.display = "none";
    //itt kell egy elágazást nyitnom, hogy mit nyitok meg.
    gameArea.style.display = "flex";
    //név frissítése
    nameField.textContent = nameInput.value;
    //időzítő elindítása
    const startTime = new Date();
    intervalId = setInterval(() => updateElapsedTime(startTime), 1000);
    let currentMap;
    if(diff === 0)
    {
        currentMap = easyMaps[randomMapNumber];
        HelperMapname = randomMapNumber*1;
    }
    else
    {
        currentMap = hardMaps[randomMapNumber];
        HelperMapname = randomMapNumber*2;
    }
    //kiiratás
    for(let i = 0; i < currentMap[0].length; i++)
    {
        const rowEl = document.createElement('tr');
        for(let j= 0; j < currentMap[0].length; j++)
        {
            const cellEl = document.createElement('td');
            cellEl.classList.add('cell');
            cellEl.dataset['val'] = i * j;
            cellEl.style.width = "100px";
            cellEl.style.height = "100px";
            cellEl.style.backgroundSize = "cover";
            cellEl.style.backgroundRepeat = "no-repeat";
            cellEl.style.border = "solid 1px #a4a5ab6c";
            switch (currentMap[i][j]) {
                case "o":
                    cellEl.style.backgroundImage = "url('pics/tiles/o.png')";
                    break;
                case "f":
                    cellEl.style.backgroundImage = "url('pics/tiles/f.png')";
                    break;
                case "bHor":
                    cellEl.style.backgroundImage = "url('pics/tiles/bHor.png')";
                    break;
                case "bVer":
                    cellEl.style.backgroundImage = "url('pics/tiles/bVer.png')";
                    break;
                case "mBR":
                    cellEl.style.backgroundImage = "url('pics/tiles/mBR.png')";
                    break;
                case "mBL":
                    cellEl.style.backgroundImage = "url('pics/tiles/mBL.png')";
                    break;
                case "mTL":
                    cellEl.style.backgroundImage = "url('pics/tiles/mTL.png')";
                    break;
                case "mTR":
                    cellEl.style.backgroundImage = "url('pics/tiles/mTR.png')";
                    break;
                default:
                    console.warn(`Unknown tile type: ${currentMap[i][j]}`);
                    break;
            }
            rowEl.append(cellEl);
        }
        table.append(rowEl);
    }
}

//toplistába mentés
function saveScore(mapName, playerName, time) {
    const leaderboard = JSON.parse(localStorage.getItem("leaderboard")) || {};

    if (!leaderboard[mapName]) {
        leaderboard[mapName] = [];
    }

    leaderboard[mapName].push({ name: playerName, time: time });

    leaderboard[mapName].sort((a, b) => {
        const [aMin, aSec] = a.time.split(":").map(Number);
        const [bMin, bSec] = b.time.split(":").map(Number);
        return aMin * 60 + aSec - (bMin * 60 + bSec);
    });

    //top 3 eredmény marad csak bent
    leaderboard[mapName] = leaderboard[mapName].slice(0, 3);

    localStorage.setItem("leaderboard", JSON.stringify(leaderboard));
}

//toplista megjelenítése
function displayLeaderboard(mapName) {
    const leaderboard = JSON.parse(localStorage.getItem("leaderboard")) || {};
    const mapScores = leaderboard[mapName] || [];

    let leaderboardHtml = `<h2>Toplista az adott pályára:</h2><ul>`;
    mapScores.forEach((score, index) => {
        leaderboardHtml += `<li>${index + 1}. ${score.name} - ${score.time}</li>`;
    });
    leaderboardHtml += "</ul>";

    document.querySelector("#leaderboard-container").innerHTML = leaderboardHtml;
}

//időszámláló szép kiíratása
function updateElapsedTime(startTime) {
    const now = new Date();
    const elapsedTime = now - startTime;

    const minutes = Math.floor(elapsedTime / (1000 * 60));
    const seconds = Math.floor((elapsedTime / 1000) % 60);
    if(minutes === 0)
    {
        if(seconds<10)
        {
            timeField.textContent =`00 : 0${seconds}`;
        }
        else
        {
            timeField.textContent =`00 : ${seconds}`;
        }
    }
    else if(minutes < 10)
    {
        if(seconds<10)
        {
            timeField.textContent =`0${minutes} : 0${seconds}`;
        }
        else
        {
            timeField.textContent =`0${minutes} : ${seconds}`;
        }
    }
    else
    {
        if(seconds<10)
        {
            timeField.textContent =`${minutes} : 0${seconds}`;
        }
        else
        {
            timeField.textContent =`${minutes} : ${seconds}`;
        }
    }
}