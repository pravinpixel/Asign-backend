$('.asign_request_view').on('click',function() {
   if ($('.asign_request_view').prop('checked')) {
      $('.inspection_requests_view').prop('checked',true);
      $('.label_requests_view').prop('checked',true);
   } 
   else {
      $('.asign_request_view').prop('checked',false);
      $('.inspection_requests_view').prop('checked',false);
      $('.label_requests_view').prop('checked',false);
   }
});
$('.inspection_requests_view').on('click',function() {
   if ($('.inspection_requests_view').prop('checked')) {
      $('.label_requests_view').prop('checked',true);
   } 
   else {
      $('.inspection_requests_view').prop('checked',false);
      $('.label_requests_view').prop('checked',false);
   }
});
$(".all_stock input[type='checkbox']").on("click", function() {
   var check_module=$(this).val();
   var values =check_module.split(".");
   var firstValue = values[0];
   var secondValue = values[1]; 
   if( $('input[type="checkbox"][value="'+check_module+'"]').prop('checked') &&  secondValue == 'create' ) {
      $('input[type="checkbox"][value="'+firstValue+'.edit"]').prop('checked', true);
      $('input[type="checkbox"][value="'+firstValue+'.view"]').prop('checked', true);
   }else {
      if(secondValue == 'create'){
         $('input[type="checkbox"][value="'+firstValue+'.edit"]').prop('checked', false);
         $('input[type="checkbox"][value="'+firstValue+'.view"]').prop('checked', false);
      }
   }
   if( $('input[type="checkbox"][value="'+check_module+'"]').prop('checked') && secondValue == 'edit' ) {
      $('input[type="checkbox"][value="'+firstValue+'.view"]').prop('checked', true);
   }else {
      if(secondValue == 'edit'){
         $('input[type="checkbox"][value="'+firstValue+'.view"]').prop('checked', false);
      }
   }
});
$(".all_label input[type='checkbox']").on("click", function() {
   var check_module=$(this).val();
   var values =check_module.split(".");
   var firstValue = values[0];
   var secondValue = values[1]; 
   if( $('input[type="checkbox"][value="'+check_module+'"]').prop('checked') &&  secondValue == 'create' ) {
      $('input[type="checkbox"][value="'+firstValue+'.edit"]').prop('checked', true);
      $('input[type="checkbox"][value="'+firstValue+'.view"]').prop('checked', true);
   }else {
      if(secondValue == 'create'){
         $('input[type="checkbox"][value="'+firstValue+'.edit"]').prop('checked', false);
         $('input[type="checkbox"][value="'+firstValue+'.view"]').prop('checked', false);
      }
   }
   if( $('input[type="checkbox"][value="'+check_module+'"]').prop('checked') && secondValue == 'edit' ) {
      $('input[type="checkbox"][value="'+firstValue+'.view"]').prop('checked', true);
   }else {
      if(secondValue == 'edit'){
         $('input[type="checkbox"][value="'+firstValue+'.view"]').prop('checked', false);
      }
   }
});
$(".all_master input[type='checkbox']").on("click", function() {
   var check_module=$(this).val();
   var values =check_module.split(".");
   var firstValue = values[0];
   var secondValue = values[1]; 
   if( $('input[type="checkbox"][value="'+check_module+'"]').prop('checked') &&  secondValue == 'create' ) {
      $('input[type="checkbox"][value="'+firstValue+'.edit"]').prop('checked', true);
      $('input[type="checkbox"][value="'+firstValue+'.view"]').prop('checked', true);
   }else {
      if(secondValue == 'create'){
         $('input[type="checkbox"][value="'+firstValue+'.edit"]').prop('checked', false);
         $('input[type="checkbox"][value="'+firstValue+'.view"]').prop('checked', false);
      }
   }
   if( $('input[type="checkbox"][value="'+check_module+'"]').prop('checked') && secondValue == 'edit' ) {
      $('input[type="checkbox"][value="'+firstValue+'.view"]').prop('checked', true);
   }else {
      if(secondValue == 'edit'){
         $('input[type="checkbox"][value="'+firstValue+'.view"]').prop('checked', false);
      }
   }
});
$(".all_user input[type='checkbox']").on("click", function() {
   var check_module=$(this).val();
   var values =check_module.split(".");
   var firstValue = values[0];
   var secondValue = values[1]; 
   if( $('input[type="checkbox"][value="'+check_module+'"]').prop('checked') &&  secondValue == 'create' ) {
      $('input[type="checkbox"][value="'+firstValue+'.edit"]').prop('checked', true);
   }else {
      if(secondValue == 'create'){
         $('input[type="checkbox"][value="'+firstValue+'.edit"]').prop('checked', false);
      }
   } 
});
$(".all_role input[type='checkbox']").on("click", function() {
   var check_module=$(this).val();
   var values =check_module.split(".");
   var firstValue = values[0];
   var secondValue = values[1]; 
   if( $('input[type="checkbox"][value="'+check_module+'"]').prop('checked') &&  secondValue == 'create' ) {
      $('input[type="checkbox"][value="'+firstValue+'.edit"]').prop('checked', true);
   }else {
      if(secondValue == 'create'){
         $('input[type="checkbox"][value="'+firstValue+'.edit"]').prop('checked', false);
      }
   } 
});
// #..asign...
function groupcheck(module_id,module_class,module_value){
$("#"+module_id).on("change", function(e) {
document.querySelectorAll("."+module_class+" input[type='checkbox']").forEach(function (checkbox) {
      if( $('input[type="checkbox"][value="'+module_value+'"]').prop('checked')) {
         if(checkbox.value!="stock-overview.create"){
       $('input[type="checkbox"][value="'+checkbox.value+'"]').prop('checked', true);
          }
      }else{
          if(checkbox.value!="stock-overview.create"){
       $('input[type="checkbox"][value="'+checkbox.value+'"]').prop('checked', false);
          }
      }
});
});
}

$(".all_asign input[type='checkbox']").on("click", function() {
var parent=$('input[type="checkbox"][value="module_asign"]');
if($(this).val() != 'module_asign'){
var checkboxes=document.querySelectorAll(".all_asign input[type='checkbox']:not(.module_asign)");
var tolatcheckCount=checkboxes.length;
var checkedCount=Array.from(checkboxes).filter(checkbox => checkbox.checked).length;
if(tolatcheckCount != checkedCount){
parent.prop('checked',false);
}
if(tolatcheckCount == checkedCount){
parent.prop('checked',true);
}
}
});
$(".all_stock input[type='checkbox']").on("click", function() {

var parent=$('input[type="checkbox"][value="module_stock"]');
if($(this).val() != 'module_stock'){
var checkboxes=document.querySelectorAll(".all_stock input[type='checkbox']:not(.module_stock)");
var tolatcheckCount=checkboxes.length;
var checkedCount=Array.from(checkboxes).filter(checkbox => checkbox.checked).length;
if(tolatcheckCount != checkedCount){
parent.prop('checked',false);
}
if(tolatcheckCount == checkedCount){
parent.prop('checked',true);
}
}
});
$(".all_label input[type='checkbox']").on("click", function() {

var parent=$('input[type="checkbox"][value="module_label"]');
if($(this).val() != 'module_label'){
var checkboxes=document.querySelectorAll(".all_label input[type='checkbox']:not(.module_label)");
var tolatcheckCount=checkboxes.length;
var checkedCount=Array.from(checkboxes).filter(checkbox => checkbox.checked).length;
if(tolatcheckCount != checkedCount){
parent.prop('checked',false);
}
if(tolatcheckCount == checkedCount){
parent.prop('checked',true);
}
}
});
$(".all_master input[type='checkbox']").on("click", function() {

var parent=$('input[type="checkbox"][value="module_master"]');
if($(this).val() != 'module_master'){
var checkboxes=document.querySelectorAll(".all_master input[type='checkbox']:not(.module_master)");
var tolatcheckCount=checkboxes.length;
var checkedCount=Array.from(checkboxes).filter(checkbox => checkbox.checked).length;
if(tolatcheckCount != checkedCount){
parent.prop('checked',false);
}
if(tolatcheckCount == checkedCount){
parent.prop('checked',true);
}
}
});
$(".all_user input[type='checkbox']").on("click", function() {

var parent=$('input[type="checkbox"][value="module_user"]');
if($(this).val() != 'module_user'){
var checkboxes=document.querySelectorAll(".all_user input[type='checkbox']:not(.module_user)");
var tolatcheckCount=checkboxes.length;
var checkedCount=Array.from(checkboxes).filter(checkbox => checkbox.checked).length;
if(tolatcheckCount != checkedCount){
parent.prop('checked',false);
}
if(tolatcheckCount == checkedCount){
parent.prop('checked',true);
}
}
});
$(".all_role input[type='checkbox']").on("click", function() {

var parent=$('input[type="checkbox"][value="module_role"]');
if($(this).val() != 'module_role'){
var checkboxes=document.querySelectorAll(".all_role input[type='checkbox']:not(.module_role)");
var tolatcheckCount=checkboxes.length;
var checkedCount=Array.from(checkboxes).filter(checkbox => checkbox.checked).length;
if(tolatcheckCount != checkedCount){
parent.prop('checked',false);
}
if(tolatcheckCount == checkedCount){
parent.prop('checked',true);
}
}
});