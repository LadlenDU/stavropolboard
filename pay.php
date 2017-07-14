<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta https-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pay</title>
</head>

<body>
<form id=pay name=pay method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp"> 
<p>пример платежа через сервис Web Merchant Interface</p> <p>заплатить 1 WMZ...</p> 
<p>
  <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="1.0">
  <input type="hidden" name="LMI_PAYMENT_DESC" value="тестовый платеж">
  <input type="hidden" name="LMI_PAYMENT_NO" value="1">
  <input type="hidden" name="LMI_PAYEE_PURSE" value="Z145179295679">
  <input type="hidden" name="LMI_SIM_MODE" value="0"> 
</p> 
<p>
 <input type="submit" value="submit">
 </p> 
</form> 
</body>
</html>