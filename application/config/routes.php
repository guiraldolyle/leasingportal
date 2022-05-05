<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = "portal/index";
$route['404_override']       = '';

# BASE ROUTES
$route['check_login']     = "portal/check_login";
$route['logout']          = "portal/logout";

# PORTAL ROUTES
$route['tenant_soa']                            = "portal/tenant_soa";
$route['rr_ledger']                             = "portal/rr_ledger";
$route['utilityReadings']                       = "portal/utilityReadings";
$route['user_credentials']                      = "portal/user_credentials";
$route['getTenantSoa']                          = "portal/get_tenantSoa";
$route['updateUserSettings/(:any)']             = "portal/update_usettings_tenant/$1";
$route['getForwardedBalance/(:any)/(:any)']     = "portal/get_forwarded_balance/$1/$2";
$route['get_ledgerTenant/(:any)/(:any)/(:any)'] = "portal/get_ledgerTenant/$1/$2/$3";
$route['get_payment_details/(:any)/(:any)']     = "portal/get_payment_details/$1/$2";
$route['getReading/(:any)/(:any)/(:any)']       = "portal/getReading/$1/$2/$3";

# PAYMENT ADVICE PORTAL
$route['paymentAdvice']     = "portal/paymentAdvice";
$route['savePaymentAdvice'] = "portal/savePaymentAdvice";
$route['soa_balances']      = "portal/soa_balances";


# - PORTAL ADMIN ROUTES
$route['admin_tenants'] = "portal/admin_tenants";
# - - SOA
$route['getSoa/(:any)'] = "portal/getSoa/$1";
$route['admin_soa']     = "portal/admin_soa";
# - - INVOICE
$route['admin_invoices'] = "portal/admin_invoices";
$route['perStore']       = "portal/perStore";
$route['allData']        = "portal/allData";
$route['perTenant']      = "portal/perTenant";
$route['uploadAllInvoiceData'] = "portal/uploadAllInvoiceData";
$route['getTenants']           = "portal/getTenants";

$route['perTenantUpload'] = "portal/perTenantUpload";


# - - PAYMENT
$route['admin_payment'] = "portal/admin_payment";
$route['admin_paymentPerStore'] = "portal/admin_paymentPerStore";
$route['get_payment']   = "portal/get_payment";
$route['get_paymentPerStore']   = "portal/get_paymentPerStore";
$route['get_soa']                  = "portal/get_soa";
$route['uploadSoaData/(:any)']     = 'portal/uploadSoaData/$1';
$route['uploadPaymentData/(:any)'] = 'portal/uploadPaymentData/$1';
$route['search_tradeName']         = "portal/search_tradeName";

$route['translate_uri_dashes'] = FALSE;

# MARCH 31, 2022
# PAYMENT ADVICE ADMIN ROUTES
$route['getAdvices/(:any)/(:any)'] = "portal/getAdvices/$1/$2";
$route['postAdvice']               = "portal/postAdvice";
$route['notices']                  = "portal/notices";
$route['getTenantsMulti']          = "portal/getTenantsMulti";

# OTHERS ROUTES
$route['uploadLeasingData'] = "portal/uploadLeasingData";
$route['uploadhistory'] = "portal/uploadhistory";
$route['exportPaymentAdvice'] = "portal/exportPaymentAdvice";

$route['uploadCheckedSoa']     = "portal/uploadCheckedSoa";
$route['uploadCheckedPayment'] = "portal/uploadCheckedPayment";

# LEASING PORTAL 1.2 ROUTES
$route['leasingportal2'] = "LeasingPortalControllers/MainController/index";
$route['home'] = "LeasingPortalControllers/MainController/home";
$route['mysoa'] = "LeasingPortalControllers/MainController/mysoa";
$route['myledger'] = "LeasingPortalControllers/MainController/myledger";
$route['utilityreadings'] = "LeasingPortalControllers/MainController/utilityreadings";
$route['paymentadvice'] = "LeasingPortalControllers/MainController/paymentadvice";


#temporary
$route['blastSMSEmail'] = "portal/blastSMSEmail";
