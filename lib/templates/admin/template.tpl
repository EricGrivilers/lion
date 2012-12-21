<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin</title>
{headerScripts}
{styles}</head>

<body>
{addOns}
<div class="container">
  <div class="header"><ul><li><a href='/admin'>Biens</a></li><li>Localités</li><li>Utilisateurs</li></ul>
  Ref.<input type='text' id='searchRef' value='030/' /><input type='button' onclick='$.admin.searchItem()' value='ok' />
    <!-- end .header --></div>
  <div class="content"><div id='editorForm'>
{mainContent}
</div>
    <!-- end .content --></div>
  <div class="footer">
    <p>Footer</p>
    <!-- end .footer --></div>
  <!-- end .container --></div>
</body>
</html>
