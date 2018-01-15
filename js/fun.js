function delete_record($id) {
  if (confirm("Ви дійсно бажаете ВИДАЛИТИ ? ") == false) { return; }
  document.location.href = window.location.pathname+"?del_id="+$id;
}
// видалення членів сім'ї
function Delete_Family($id) {
  if (confirm("Ви дійсно бажаете ВИДАЛИТИ ? ") == false) { return; }
  document.location.href = window.location.pathname+"?FamilyID="+$id;
}
// видалення освіти
function Delete_Education($id) {
  if (confirm("Ви дійсно бажаете ВИДАЛИТИ ? ") == false) { return; }
  document.location.href = window.location.pathname+"?EducationID="+$id;
}
// калькулятор штатов
function calcshtat() {
  Officer   = parseFloat(document.getElementById('Shtat_Officer').value);
  Sergeant  = parseFloat(document.getElementById('Shtat_Sergeant').value);
  Soldier   = parseFloat(document.getElementById('Shtat_Soldier').value);
  Employees = parseFloat(document.getElementById('Shtat_Employees').value);
  //
  Total        = (Officer+Sergeant+Soldier+Employees);
  // вывод результата 
  document.getElementById('Total').value  = (Total).toFixed(0);
}
// калькулятор укомплектованности
function calcstaffing() {
  // список
  OfficerK   = parseFloat(document.getElementById('List_OfficerK').value);
  OfficerMob = parseFloat(document.getElementById('List_OfficerMob').value);
  SoldierK   = parseFloat(document.getElementById('List_SoldierK').value);
  SoldierMob = parseFloat(document.getElementById('List_SoldierMob').value);
  Employees  = parseFloat(document.getElementById('List_Employees').value);
  // подсчет
  TotalOfficer = (OfficerK + OfficerMob);
  TotalSoldier = (SoldierK + SoldierMob);
  Total        = (TotalOfficer + TotalSoldier + Employees);
  // вывод результата 
  document.getElementById('Total').value        = (Total).toFixed(0);
  document.getElementById('TotalOfficer').value = (TotalOfficer).toFixed(0);
  document.getElementById('TotalSoldier').value = (TotalSoldier).toFixed(0);
}
// перевірка інтервала дат = закінчення завжди більше початку
function CheckDate() {
  if (document.getElementById('DateEnd').value < document.getElementById('DateBegin').value ) {
    document.getElementById('DateEnd').value = null;
    alert ("ПОМИЛКА - ДАТА ЗАКІНЧЕННЯ МЕНЬШЕ ДАТИ ПОЧАТКУ!");
  }
}

function get_awarding() {
  AwardsType_ID = $("#AwardsType_ID").val();
  var params = 'AwardsType_ID=' + encodeURIComponent(AwardsType_ID);
  var link = 'xhr_Awarding.php?r='+Math.random();
  var xhr = new XMLHttpRequest();
  xhr.open("POST", link, true);
  xhr.responseType = 'text';
  xhr.onload = function(e) {
    if (xhr.readyState== 4 && xhr.status == 200) {
      $("#span_Awarding").html(xhr.response);
    }
  };
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send(params);
}

