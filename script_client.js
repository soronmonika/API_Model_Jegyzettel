//dolgozók kinyerése API-ból


//ezen keresztül megkapjuk az api hívást, így ez alapján megkell kapnunk
var xhr=new XMLHttpRequest(); //API hívás létrehozésa(adatt lekérés szervertől) || ez lesz az „adatpostás”, aki elmegy a szerverhez adatért.
xhr.open ("GET", "http://127.0.0.1/KCS_202507/F26_2025_10_27_API/php6/feladat4_API/"); //GET-> adatot szeretnénk lekérdni. URL-> honnan kérjük az adatot(API címe, ami JSON-t ad vissza)

//Amikor meg jön a válasz a szervertől: xhr.onreadystatechange → akkor fut le, amikor az állapot változik.
xhr.onreadystatechange=function(){
  if(xhr.readyState==4 && xhr.status==200){ //readyState == 4 → az adat teljesen megérkezett. status == 200 → a kérés sikeres (HTTP OK).
    //A kapott JSON adat átalakítása használható objektummá
    console.log(JSON.parse(xhr.responseText)); //→ átalakítjuk valódi JavaScript tömbbé/objektummá.||xhr.responseText → a szervertől visszakapott szöveg (JSON).
      var elemek=JSON.parse(xhr.responseText);
      //dinamikusan össze rakjuk a törzsöt
      for(var i=0;i<elemek.length; i++){
      var tr=document.createElement("tr");
      var td1=document.createElement("td");
      var td2=document.createElement("td");
      var td3=document.createElement("td");
      var td4=document.createElement("td");
      var td5=document.createElement("td");
      var td6=document.createElement("td");
      var td7=document.createElement("td");
      var td8=document.createElement("td");

        //Adatokat beillesztése a cellákba::
      td1.appendChild(document.createTextNode(elemek[i].empno))
      td2.appendChild(document.createTextNode(elemek[i].ename))
      td3.appendChild(document.createTextNode(elemek[i].job))
      td4.appendChild(document.createTextNode(elemek[i].mgr))
      td5.appendChild(document.createTextNode(elemek[i].hiredate))
      td6.appendChild(document.createTextNode(elemek[i].sal))
      td7.appendChild(document.createTextNode(elemek[i].comm))
      td8.appendChild(document.createTextNode(elemek[i].daptno))

      //A cellák hozzáadása a sorhoz, a sor hozzáadása a táblához
        tr.appendChild(td1)
        tr.appendChild(td2)
        tr.appendChild(td3)
        tr.appendChild(td4)
        tr.appendChild(td5)
        tr.appendChild(td6)
        tr.appendChild(td7)
        tr.appendChild(td8)

        document.getElementById("torzs").appendChild(tr);
      }
  }
};
xhr.send(null); //lekérdezés elküldése

//Dolgozók feltöltése API-n keresztül:

function doApiCall(proc, methid, params){ //(milyen folyamat, metódus irány, paraméterekkel)
  var fetchParam;
  if(method=="GET"){ //kapjuk az adatokat
    //a fejléc részt feltöltöjük azokkal az adatokkal, amivel az oldal tudja melyik folyamat zajlik.
    fetchParam={
      headers:{
        "Content-type" : "application/json"
      },
      method: "GET",
      cache: "no-cache",
      mode: "no-cors",
      redirect:"follow"
    };
  }
  else{
        fetchParam={
      headers:{
        "Content-type" : "application/json"
      },
      method: method,
      body: JSON.stringify(params),//feldolgozásban kapott adatokat JSON-né alakítjuk || vissza adjuk lent
      cache: "no-cache",
      //mode: "no-cors",
      redirect:"follow"
    };
  }

//  return fetch(http://127.0.0.1/KCS_202507/F26_2025_10_27_API_kinyeri%20az%20adatokatEgyT%c3%a1bl%c3%a1ba_AjaxH%c3%adv%c3%a1sSeg%c3%adts%c3%a9g%c3%a9vel/php6/feladat4_API/index.php?method=" + proc fetchParam);
}

//vissza ad egy nagy onjektumot, amivel vissza adja az adatokat:

function GetFormData(){
  return{
    empno: (document.getElementById("empno").value !="") ? document.getElementById("empno").value : null,
    ename: (document.getElementById("ename").value !="") ? document.getElementById("ename").value : null,
    job: (document.getElementById("job").value !="") ? document.getElementById("job").value : null,
    hiredate: (document.getElementById("hiredate").value !="") ? document.getElementById("hiredate").value : null,
    mgr: (document.getElementById("mgr").value !="") ? document.getElementById("mgr").value : null,
    sal: (document.getElementById("sal").value !="") ? document.getElementById("sal").value : null,
    comm: (document.getElementById("comm").value !="") ? document.getElementById("comm").value : null,
    deptno: (document.getElementById("deptno").value !="") ? document.getElementById("deptno").value : null,
  }
}

function SetEmp(){
  var data= GetFormData();
  doApiCall("setemp", "POST", data);
}

function DelEmp(){
  var data=GetFormData();
  doApiCall("delemp", "delete", {
    empno: Number(data.empno)
  });
}

function UpdateEmp(){ //(amit nem módosítunk sort, azt null-ának írja)
  var data=GetFormData();
  var kulcsok= Object.keys(data);

  for(var i=0; i<kulcsok.length; i++){
      if(data[kulcsok[i]]==null){ //megnézzük, hogy a kulcsok, a data-ba nulla-e
          delete data[kulcsok[i]]; //ha igen, akkor végig megy.
      }
  }


 /* //kivesszük a nullát, és azt adja át amit módosítottunk
  if(data.empno==null){
  delete data["empno"];
}
  if(data.name==null){
  delete data["name"];
}
  if(data.job==null){
  delete data["job"];
}
  if(data.mgr==null){
  delete data["mgr"];
}
  if(data.hiredate==null){
  delete data["hiredate"];
}
  if(data.sal==null){
  delete data["sal"];
}
  if(data.comm==null){
  delete data["comm"];
}
  if(data.deptno==null){
  delete data["deptno"];
}*/
  doApiCall("modemp", "PUT", data);
}
