// JavaScript Document

function OnKeyPress(field, event)
{
if (event.keyCode == 13) {
for (i = 0; i < field.form.elements.length; i++)
if (field.form.elements[i].tabIndex == field.tabIndex+1) 
{
field.form.elements[i].focus();
if(field.form.elements[i].type == "text" || field.form.elements[i].type == "number")
field.form.elements[i].select();
break;
}
return false;
}
return true;
}
