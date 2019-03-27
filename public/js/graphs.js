window.addEventListener('DOMContentLoaded', init, false);
var chart1=null;
var chart2=null;
function init(){
    
    var period ='Daily';
    initializeSums();

    initializePeriodSwitcher();
    initializeGraphs(period);

    //filterVendorsAndPlebs();

}
 function initializePeriodSwitcher(){
    var btnGroup=document.getElementById("periodSwitcher"); 
    btns=btnGroup.getElementsByClassName("period-switcher-btn");
    for (var i=0;i<btns.length;i++){
        btns[i].addEventListener("click",function(){
            var curr = document.getElementsByClassName("chosen");
            
            var period= this.dataset.period;
            initializeGraphs(period);
            
            curr[0].className=curr[0].className.replace(" chosen","");
            this.className += " chosen";
            
        });
    }
 }
function generateFutureMonth(period,date){
    month=[];
    lastDate=date;
    if (period=='Daily'){
        for (var i=0;i<30;i++){
            momentDate=moment(lastDate,'YYYY-MM-DD').add(1,'d').format('YYYY-MM-DD');
            month.push(momentDate);
            lastDate=momentDate;
        }
    }
    else if (period=='Weekly'){
        for (var i=0;i<4;i++){
            momentDate=moment(lastDate,'YYYY-MM-DD').add(1,'w').format('YYYY-MM-DD');
            month.push(momentDate);
            lastDate=momentDate;
        }
    }
    else if (period=='Monthly'){
        for (var i=0;i<1;i++){
            momentDate=moment(lastDate,'YYYY-MM-DD').add(1,'m').format('YYYY-MM-DD');
            month.push(momentDate);
            lastDate=momentDate;
        }
    }
    return month;
}
function initializeGraphs(period){
    var context;
    var source;
    if(chart1 != null){
        chart1.destroy();
    }
    else if (chart2 != null){
        chart2.destroy();
    }
    if(period=='Daily'){
        
        context=document.getElementById('medians').getContext('2d');
        source = data.data.aggregates.medianes.daily;
        preds=Object.values(data.data.aggregates.predictions.daily);
        createTimeSeriesGraph(period,context,source,preds,'Daily average prices'); 

        context=document.getElementById('sums').getContext('2d');
        source = data.data.aggregates.sums.daily;
        createTimeSeriesSumGraph(context,source,'Total sold each day');
    }
    else if (period=='Weekly'){
        preds=Object.values(data.data.aggregates.predictions.weekly);       
        var context=document.getElementById('medians').getContext('2d');
        var source = data.data.aggregates.medianes.weekly;
        createTimeSeriesGraph(period,context,source,preds,'Weekly average prices');

        var context=document.getElementById('sums').getContext('2d');
        var source = data.data.aggregates.sums.weekly;
        createTimeSeriesSumGraph(context,source,'Total sold each week');
    }
    else if (period=='Monthly'){
        preds=Object.values(data.data.aggregates.predictions.monthly);
        var context=document.getElementById('medians').getContext('2d');
        var source = data.data.aggregates.medianes.montlhy;
        createTimeSeriesGraph(period,context,source,preds,'Monthly average prices');

        var context=document.getElementById('sums').getContext('2d');
        var source = data.data.aggregates.sums.monthly;
        createTimeSeriesSumGraph(context,source,'Total sold each month');        
    }
}

function createTimeSeriesGraph(period,context,source,preds,label){
    var labelHalf=[];
    var chartData=[];
    //sepparating predictions in two to show it in two different colours

    
    for (var k in source){
        if (source.hasOwnProperty(k)) {
            date=new Date(k)
            labelHalf.push(getDateFromISO(date));
            chartData.push(source[k]);
        }
    }
    var labels=labelHalf.concat(generateFutureMonth(period,labelHalf[labelHalf.length-1]));
    predictions=generateTwoDatasets(preds,labelHalf);
    chart1 = new Chart(context,{
        type: 'line',
        // The data for our dataset
        data: {
            labels: labels,
            datasets: 
            [{
                label:'Linear regression', 
                backgroundColor: '#19486B',
                borderColor: '#19486B',
                data:predictions[0],
                fill:false
            },
            {
                label:'Predictions', 
                backgroundColor: '#01B78B',
                borderColor: '#01B78B',
                data:predictions[1],
                fill:false
            },
            {
                label: label,
                backgroundColor: '#458E91',
                borderColor: '#458E91',
                data: chartData,
            }]
        },
        // Configuration options go here
        options: {}
    });
}
function createTimeSeriesSumGraph(context,source,label){
    var labels=[];
    var chartData=[];
    for (var k in source){
        if (source.hasOwnProperty(k)) {
            date=new Date(k)
            labels.push(getDateFromISO(date));
            chartData.push(source[k]);
        }
    }
    chart2 = new Chart(context,{
        type: 'line',
        // The data for our dataset
        data: {
            labels: labels,
            datasets: [{
                label: label,
                backgroundColor: '#B3B3B3',
                borderColor: '#B3B3B3',
                data: chartData,
            }]
        },
        // Configuration options go here
        options: {}
    });
}

function getDateFromISO(date){
    year = date.getFullYear();
    month = date.getMonth()+1;
    dt = date.getDate();

    if (dt < 10) {
    dt = '0' + dt;
    }
    if (month < 10) {
    month = '0' + month;
    }
    return year+'-' + month + '-'+dt;
}
function initializeSums() {
    var sums = data.data.aggregates.sums;
    var monthlyMedianesArray=Object.values(data.data.aggregates.medianes.montlhy);
    var daysPredictionsArray=Object.values(data.data.aggregates.predictions.daily);
    dailyMeds=Object.keys(data.data.aggregates.medianes.daily);
    document.getElementById("itemsRetrieved").innerHTML=data.data.items.length;
    document.getElementById("totalSoldSince").innerHTML=data.data.items[data.data.items.length-1].end_time;
    document.getElementById("totalSoldGBP").innerHTML="£ "+sums.all_time.toFixed(2);
    document.getElementById("currentPrice").innerHTML="£ "+monthlyMedianesArray[monthlyMedianesArray.length-1].toFixed(2);
    document.getElementById("predictedPrice").innerHTML="£ "+daysPredictionsArray[daysPredictionsArray.length-1].toFixed(2);
    document.getElementById("predictionWidget").innerHTML=moment(dailyMeds[dailyMeds.length-1]).add(30,'d').format('dddd, MMMM Do');;
}
function generateTwoDatasets(fullLabel,halfLabel){
    arr1=[];
    arr2=[];
    for(var i=0;i<fullLabel.length;i++){
        if (i<halfLabel.length){
            arr1.push(fullLabel[i]);
            arr2.push(null);
        }
        else {
            arr1.push(null);
            arr2.push(fullLabel[i]);
        }
        
    }
    arr2[halfLabel.length-1]=fullLabel[halfLabel.length-1];
    return [arr1,arr2];
}
function filterVendorsAndPlebs(){
    //chislo plebeev i chislo vendorov
    var sellers= data.data.sellers.seller_sales_count;
    var sellerList =0;
    var plebList=0;
    for (var k in sellers){
        if (sellers.hasOwnProperty(k)) {
            if (sellers[k]==1){
                plebList+=1;
            }
            else {
                sellerList+=sellers[k];
            }
        }
    }
    new Chart(document.getElementById("vendToPleb"), {
    type: 'pie',
    data: {
      labels: ["Sellers", "Buyers"],
      datasets: [{
        label: "Amount of deals between vendors and regular users",
        backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
        data: [sellerList,plebList]
      }]
    },
    options: {
      title: {
        display: true,
        text: 'Amount of deals between vendors and regular users within last 3 months'
      }
    }
});
}

