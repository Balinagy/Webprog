/*pályák leképezése
    f-üres mező
    o-oázis
    bHor-híd mező (vízszintes)
    bVer-híd mező (függőleges)
    mTR-hegy mező (a kanyarnak felül és jobbra van kimenete)
    mTL-hegy mező (a kanyarnak felül és balra van kimenete)
    mBR-hegy mező (a kanyarnak alul és jobbra van kimenete)
    mBL-hegy mező (a kanyarnak alul és balra van kimenete)
*/

const easyMap1 =[
["f","mBL","f","f","o"],
["f","f","f","bVer","o"],
["bVer","f","mTL","f","f"],
["f","f","f","o","f"],
["f","f","mTR","f","f"]
]

const easyMap2 = [
["o", "f", "bHor", "f", "f"],
["f", "mTL", "f", "f", "mTL"],
["bVer", "o", "mTR", "f", "f"],
["f", "f", "f", "o", "f"],
["f", "f", "f", "f", "f"]
];

const easyMap3 = [
["f", "f", "bHor", "f", "f"],
["f", "f", "f", "f", "bVer"],
["f", "mTL", "bVer", "f", "f"],
["f", "o", "f", "f", "f"],
["f", "bHor", "f", "f", "mTL"]
];

const easyMap4 = [
["f", "f", "f", "bHor", "f"],
["f", "f", "f", "f", "f"],
["bVer", "f", "mBL", "f", "mBL"],
["f", "f", "f", "f", "f"],
["f", "f", "o", "mTR", "f"]
];

const easyMap5 = [
["f", "f", "bHor", "f", "f"],
["f", "mBR", "f", "f", "f"],
["bVer", "f", "f", "mTR", "f"],
["f", "f", "bVer", "o", "f"],
["f", "mTL", "f", "f", "f"]
];

//nehéz pályák:

const hardMap1 = [
["f", "mBL", "o", "o", "f", "bHor", "f"],
["bVer", "f", "f", "f", "f", "f", "f"],
["f", "f", "bVer", "f", "f", "f", "f"],
["f", "f", "f", "mTR", "f", "f", "f"],
["mTR", "f", "mBL", "f", "bHor", "f", "o"],
["f", "f", "f", "f", "f", "f", "f"],
["f", "f", "f", "bHor", "f", "f", "f"]
];

const hardMap2 = [
["f", "f", "o", "f", "f", "f", "f"],
["bVer", "f", "bHor", "f", "f", "mTL", "f"],
["f", "f", "bHor", "f", "f", "f", "bVer"],
["mBR", "f", "f", "f", "f", "f", "f"],
["f", "o", "f", "mBL", "f", "f", "f"],
["f", "mBR", "f", "f", "f", "f", "f"],
["f", "f", "o", "f", "f", "f", "f"]
];

const hardMap3 = [
["f", "f", "bHor", "f", "f", "f", "f"],
["f", "f", "f", "f", "f", "f", "bVer"],
["o", "f", "mTR", "f", "f", "f", "f"],
["f", "f", "f", "f", "f", "f", "f"],
["f", "o", "mTR", "f", "bHor", "f", "f"],
["bVer", "f", "f", "f", "f", "mBL", "f"],
["f", "f", "o", "mTR", "f", "f", "f"]
];

const hardMap4 = [
["f", "f", "f", "f", "f", "f", "f"],
["f", "f", "f", "bVer", "f", "mTL", "f"],
["f", "f", "mTR", "f", "f", "f", "f"],
["f", "bHor", "f", "o", "f", "bHor", "f"],
["f", "f", "mTL", "f", "mBL", "f", "f"],
["bVer", "f", "f", "f", "f", "mTR", "f"],
["f", "f", "f", "f", "f", "f", "f"]
];

const hardMap5 = [
["f", "f", "f", "f", "f", "f", "f"],
["f", "f", "f", "f", "f", "mBR", "f"],
["f", "bHor", "bHor", "f", "mBL", "f", "f"],
["f", "f", "f", "f", "f", "f", "f"],
["f", "f", "mBR", "f", "o", "f", "f"],
["f", "mTL", "f", "bVer", "f", "f", "f"],
["f", "f", "f", "f", "f", "f", "f"]
];

const easyMaps = [easyMap1,easyMap2,easyMap3,easyMap4,easyMap5];
const hardMaps = [hardMap1,hardMap2,hardMap3,hardMap4,hardMap5];

//végignéztem minden mapot, jól vannak lekódolva, meg lehet hívni a random kiválasztást