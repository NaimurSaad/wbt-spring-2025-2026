let a = 1;
let b = 2;
[a,b] = [b,a];
console.log(`a: ${a} b: ${b}`)


function square(n){
    return n*n;
}
for(let i =1;i<11;i++){
    console.log(square(i)); 
}

const nums = [10,50,80,30,60];
let lnum = nums[0];

for (const num of nums){
    if(num>lnum)
        num>lnum ? lnum = num : lnum = lnum;
}
console.log(`Largest: ${lnum}`)