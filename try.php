
<!DOCTYPE HTML>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta http-equiv="Content-language" content="en"/>
    <meta name="robots" content="noindex, nofollow"/>
    <meta name="description" content="..."/>

    <title>Instafin</title>

        <!-- start: Mobile Specific -->
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- end: Mobile Specific -->

    <!-- start: CSS -->
    <link id="bootstrap-style" href="https://cdn.instafin.com/4aaa4fc1d1a9306487fd71f4d24d4931168cdc1e/css/bootstrap3/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.instafin.com/4aaa4fc1d1a9306487fd71f4d24d4931168cdc1e/css/bootstrap3/bootstrap-theme.min.css" rel="stylesheet">
    <link href="https://cdn.instafin.com/4aaa4fc1d1a9306487fd71f4d24d4931168cdc1e/css/theme/css/font-awesome.min.css" rel="stylesheet">
    <link id="base-style" href="https://cdn.instafin.com/4aaa4fc1d1a9306487fd71f4d24d4931168cdc1e/css/theme/css/style.css" rel="stylesheet">
    <link id="base-style-responsive" href="https://cdn.instafin.com/4aaa4fc1d1a9306487fd71f4d24d4931168cdc1e/css/theme/css/style-responsive.css" rel="stylesheet">
    <link id="instafin-style" href="https://cdn.instafin.com/4aaa4fc1d1a9306487fd71f4d24d4931168cdc1e/css/instafin.css" rel="stylesheet">
    <link id="instafin-style" href="https://cdn.instafin.com/4aaa4fc1d1a9306487fd71f4d24d4931168cdc1e/javascripts/instafin-components/styles.css" rel="stylesheet">
    <link id="table-print-style" href="https://cdn.instafin.com/4aaa4fc1d1a9306487fd71f4d24d4931168cdc1e/css/custom/transactions-and-accounts-print-style.css" rel="stylesheet" media="all">
    <link id="transaction-details-print-style" href="https://cdn.instafin.com/4aaa4fc1d1a9306487fd71f4d24d4931168cdc1e/css/custom/basic-transaction-details-print-style.css" rel="stylesheet" media="all">
    <!-- end: CSS -->

    <!-- start: Favicon -->
    <link rel="icon" type="image/x-icon" href="https://cdn.instafin.com/4aaa4fc1d1a9306487fd71f4d24d4931168cdc1e/favicon.ico">
    <!-- end: Favicon -->

    <!-- start: Javascript -->
    
        <script crossorigin="anonymous" src="https://cdn.instafin.com/4aaa4fc1d1a9306487fd71f4d24d4931168cdc1e/javascripts/browserErrorReporter.js"></script>
    

    <script> window.IN_BROWSER_TAB_ID = Date.now(); </script>
    <script crossorigin="anonymous" src="https://cdn.instafin.com/4aaa4fc1d1a9306487fd71f4d24d4931168cdc1e/javascripts/instafin-components/instafin-components.js"></script>

    
    <!-- end: Javascript -->


</head>
<body>
    

    <script>
        var menuData = [{
            id: 'dashboard',
            title: 'Dashboard',
            iconClass: 'fa fa-dashboard',
            url: '/dashboard',
            show: true
          }, {
            id: 'transactions',
            title: 'Transactions',
            iconClass: 'fa fa-handshake-o',
            show: true,

            submenuData: [{
              id: 'createTransaction',
              title: 'Enter Transaction',
              url: '#',
              show: true,
              onClickAction: function() {
                return {
                  data: { popupType: 'CreateTransaction' },
                  type: 'popups/TOGGLE_POPUP'
                }
              }
            }, {
              id: 'encashment',
              title: 'Encashment',
              url: '#',
              show: false,
              onClickAction: function() {
                return {
                  name: 'Encashment',
                  type: 'depositEncashmentPopup/SHOW_DEPOSIT_ENCASHMENT'
                }
              }
            }, {
              id: 'payIn',
              title: 'Pay In',
              url: '#',
              show: true,
              onClickAction: function() {
                  return {
                      name: 'Pay In',
                      type: 'payInTransactionPopup/SHOW_PAY_IN_TRANSACTION',
                  }
              },
            }, {
              id: 'payOut',
              title: 'Payout',
              url: '#',
              show: false,
              onClickAction: function() {
                  return {
                      name: 'Payout',
                      type: 'payoutTransactionPopup/SHOW_PAYOUT_TRANSACTION',
                  }
              },
            }]
          }, {
            id: 'create',
            title: 'Create',
            iconClass: 'fa fa-plus-square-o',
            submenuData: [{
              id: 'individual',
                title: 'Individual',
                newApp: true,
                url: '/client/individual/create',
                show: true
            }, {
                id: 'corporate',
                title: 'Corporate',
                url: '/client/corporate/create',
                show: true
            }, {
                id: 'centre',
                title: 'Centre',
                url: '/centre/create',
                show: true
            }, {
                id: 'guarantor',
                title: 'Guarantor',
                url: '/guarantor/create',
                show: true
            }]
        }, {
          id: 'tasks',
          title: 'Tasks',
          iconClass: 'fa fa-check-square',
          submenuData: [
            {
                id: 'all',
                title: 'All Tasks',
                newApp: true,
                show: true,
                url: '/tasks',
            },
            {
                id: 'create',
                title: 'Create Task',
                show: true,
                onClickAction: function() {
                  return {
                      data: { popupType: 'CreateTask' },
                      type: 'popups/TOGGLE_POPUP'
                  }
                }
            }
          ]
        }, {
          id: 'clients',
          title: 'Clients',
          iconClass: 'fa fa-group',
          submenuData: [{
              id: 'individual',
              title: 'Individual',
              url: '/client/individual/list',
              show: true
          }, {
              id: 'corporate',
              title: 'Corporate',
              url: '/client/corporate/list',
              show: true
          }, {
              id: 'centres',
              title: 'Centres',
              url: '/centre/list',
              show: true
          }]
        }, {
          id: 'bulk-actions',
          title: 'Bulk Actions',
          iconClass: 'fa fa-compress',
          show: true,
          submenuData: [{
            id: 'repayment-sheet',
            title: 'Repayment Sheet',
            url: '/transaction/account/loan/due-list'
          }, {
            id: 'deposit-sheet',
            title: 'Deposit Sheet',
            url: '/transaction/account/deposit/due-list'
          }, {
            id: 'withdrawal-sheet',
            title: 'Withdrawal Sheet',
            url: '/transaction/account/deposit/withdrawal-list'
          }, {
            id: 'combined-sheet',
            newApp: true,
            title: 'Combined Sheet',
            url: '/bulk-actions/combined-sheet'
          }, {
            id: 'create-loans',
            title: 'Create Loans',
            url: '/bulk/account/create'
          }, {
            id: 'approve-loans',
            title: 'Approve Loans',
            url: '/bulk/account/approve'
          }, {
            id: 'disburse-loans',
            title: 'Disburse Loans',
            url: '/bulk/account/disburse'
          }, {
            id: 'import-transactions',
            title: 'Import Transactions',
            url: '/transaction/migration/view',
            show: true
          }]
        }, {
          id: 'offline-transactions',
          title: 'Offline Transactions',
          iconClass: 'fa fa-mobile',
          style: {fontSize: '4em'},
          show: false,
          url: '/transaction/conflict'
        }, {
          id: 'reports',
          title: 'Reports',
          iconClass: 'fa fa-list-alt',
          show: true,
          submenuData: [{
            id: 'all-transactions',
            title: 'All Transactions',
            url: '/transaction/list',
            newApp: true
          }, {
            id: 'account-action-log',
            title: 'Account Action Log',
            url: '/reports/log/action/accounts'
          }, {
            id: 'standard-reports',
            newApp: true,
            title: 'Standard Reports',
            url: '/reports/standard-reports',
            show: true
          }, {
            id: 'dynamic-reports',
            newApp: true,
            title: 'Dynamic Reports',
            url: '/reports/dynamic-reports',
            show: true
          }]
        }, {
          id: 'accounts',
          title: 'Accounts',
          iconClass: 'fa fa-list',
          submenuData: [{
            id: 'all-accounts',
            title: 'All Accounts',
            url: '/accounts/all'
          }, {
            id: 'loan-accounts',
            title: 'Loan Accounts',
            url: '/accounts/all/loan'
          }]
        }, {
          id: 'teller',
          title: 'Teller',
          iconClass: 'custom_font-teller',
          show: false,
          submenuData: [{
            id: 'teller-accounts',
            title: 'Teller Accounts',
            url: '/teller/account/list'
          }, {
            id: 'transaction-report',
            title: 'Transaction Report',
            url: '/teller/transactions'
          }]
        }, {
          id: 'cheque-management',
          title: 'Cheque Management',
          iconClass: 'fa fa-pencil-square',
          show: false,
          submenuData: [{
            id: 'cheque-clearing',
            title: 'Cheque Clearing',
            newApp: true,
            url: '/ChequeManagement/ChequeClearing'
          }, {
              id: 'bulk-clearing',
              title: 'Bulk Inward Clearing',
              newApp: true,
              url: '/ChequeManagement/BulkClearing'
          }]
        }, {
          id: 'accounting',
          title: 'Accounting',
          iconClass: 'fa fa-table',
          show: true,
          submenuData: [{
            id: 'chart-of-accounts',
            title: 'Chart of Accounts',
            url: '/account/internal/general-ledger/list'
          }, {
            id: 'balance-sheet',
            title: 'Balance Sheet',
            url: '/account/internal/general-ledger/report/balance'
          }, {
            id: 'income-&-expense',
            title: 'Income & Expense',
            url: '/account/internal/general-ledger/report/profit-loss'
          }, {
            id: 'trial-balance-sheet',
            title: 'Trial Balance Sheet',
            url: '/account/internal/general-ledger/report/trial-balance'
          }, {
            id: 'gl-balance-report',
            title: 'GL Balance Report',
            url: '/account/internal/general-ledger/report/glbalance'
          }, {
            id: 'journal-entries',
            title: 'Journal Entries',
            newApp: true,
            url: '/account/internal/general-ledger/transaction/list'
          }, {
            id: 'gl-report',
            title: 'GL Report',
            url: '/account/internal/general-ledger/report/gltransactions'
          }, {
            id: 'provisioning-report',
            title: 'Provisioning Report',
            url: '/accounting/provisioning/report'
          }]
        }, {
          id: 'administration',
          title: 'Administration',
          iconClass: 'fa fa-cogs',
          url: '/administration',
          show: true
        }];
        var user = {
          name: 'Armando Bogayan',
          username: 'Armando-Bogayan'
        };
        var versions = {
            backend: '45739347a5de094b2e09670c2b51ce3b6b88cc11',
            frontend: 'https:\/\/cdn.instafin.com\/4aaa4fc1d1a9306487fd71f4d24d4931168cdc1e\/'
        };
        var testing = {
          timeTravelOffsetMs: 0
        };
        var environment = {
            isBehindVpn: false,
            isDevelopmentOrStaging: false,
            smdKey: '45739347a5de094b2e09670c2b51ce3b6b88cc11Armando-Bogayan'
        };
        var generalSettings = {
          currencyFormat: {
            smallestDenomination: 0.01,
            smallestDenominationForAccounting: 0.01,
            numberFormat: '#,##0.00',
            currencySymbol: '₱',
            currencyCode: 'PHP',
            currencySymbolPlacement: 'Prefix',
            denominationErrorMessage: 'The value must be rounded to 0.01',
            denominationErrorMessageForAccounting: 'The value must be rounded to 0.01',
            formatErrorMessage: 'The value provided must be in the correct format (#,##0.00)'
          },
          versions: versions,
          timeZone: 'Asia\/Manila',
          tracking: {
            piwik: {
                enabled: false,
                url: '\/\/piwik.oradian.ord\/',
                siteId: '1'
            }
          }
        };
    </script>

  <div>
    <instafin-app user={user} menu-data={menuData} general-settings={generalSettings} environment={environment} testing={testing}></instafin-app>
    <div id="content" class="span11" style="min-height: 404px;">
  <div class="row">
    <div class="col-sm-12" style="min-height: 668px;">
      <div><!-- react-empty: 92 --><!-- react-empty: 93 --><!-- react-empty: 94 -->
        <div>
        </div><!-- react-empty: 96 --><!-- react-empty: 97 --><!-- react-empty: 774 -->
        <span><!-- react-empty: 100 --></span><div class="notif__container ">
        <span>
        </span>
      </div><!-- react-empty: 103 -->
    </div>
    <div class=""><!-- react-empty: 105 -->
      <main class="User__Container--sDcoP">
        <div class="theme-modern RouteBreadcrumbs__Breadcrumbs--2-g1D">
          <ol class="breadcrumb breadcrumb__breadcrumb--1hZZh">
            <li>
              <a href="<?php echo base_url($index_php .'administrator'); ?>">Administration</a>
            </li>
            <li>
              <a href="<?php echo base_url($index_php .'users'); ?>">Users</a>
            </li>
              <li class="active">Create</li>
          </ol>
        </div>
          <div class="theme-modern Presenter__Presenter--3MNCI">
            <header class="Presenter__Header--2Cjgj">
              <h1 class="Presenter__Title--2TFqy">User</h1>
            </header><!-- react-empty: 777 -->
            <div>
              <form class="Form__Form--3RFDW FormLayout__Form--2zxkm dslPresenterForm theme-modern">
                <main>
                  <div class="Form__SectionContainer--1uXxA">
                    <section class="">
                      <section class="Section__Section--135lt Section__BorderedSection--2Q2zZ">
                        <header class="Section__Header--100R1">
                          <h3 class="Section__Title--dnZyp">Basic Info</h3>
                          <i class="fa Section__Toggle--3mQrH fa-chevron-down"></i>
                        </header>
                        <main class="Section__Items--1CIJT FormLayout__Section--VSZOD dslGroup">
                          <div class="dslField DSLForm__DslField--2BD7G Form__Field--OOguk form__formFieldRequired--2rJuy form-group">
                            <div>
                              <label for="firstName" class="control-label">First Name</label>
                            </div>
                            <div>
                              <div class="form__formComponentWrapper--1it4c">
                                <input type="text" name="firstName" value="" label="First Name" novalidate="" data-qa-element-id="firstName" id="firstName" class="form-control">
                              </div>
                              <span></span>
                            </div>
                          </div>
                          <div class="dslField DSLForm__DslField--2BD7G Form__Field--OOguk form-group">
                            <div>
                              <label for="middleName" class="control-label">Middle Name</label>
                            </div>
                            <div>
                              <div class="form__formComponentWrapper--1it4c">
                                <input type="text" name="middleName" value="" label="Middle Name" novalidate="" data-qa-element-id="middleName" id="middleName" class="form-control">
                              </div>
                              <span></span>
                            </div>
                          </div>
                          <div class="dslField DSLForm__DslField--2BD7G Form__Field--OOguk form__formFieldRequired--2rJuy form-group">
                            <div>
                              <label for="lastName" class="control-label">Last Name</label>
                            </div>
                            <div>
                              <div class="form__formComponentWrapper--1it4c">
                                <input type="text" name="lastName" value="" label="Last Name" novalidate="" data-qa-element-id="lastName" id="lastName" class="form-control">
                              </div>
                              <span></span>
                            </div>
                          </div>
                          <div class="Form__SectionContainer--1uXxA">
                            <section class="">
                              <div class="Form__SectionContainer--1uXxA">
                                <section class="FormLayout__Section--VSZOD"><!-- react-empty: 813 -->
                                  <div class="dslField DSLForm__DslField--2BD7G Form__Field--OOguk form-group">
                                    <div>
                                      <label for="optionalFields.birthDate" class="control-label">Date of Birth</label>
                                    </div>
                                    <div>
                                      <div class="form__formComponentWrapper--1it4c" data-qa-element-id="optionalFields.birthDate">
                                        <div class="DayPickerInput">
                                          <input placeholder="dd/mm/yyyy" class="DatePicker--Input" autocomplete="nope" value="">
                                        </div>
                                      </div>
                                      <span></span>
                                    </div>
                                  </div>
                                  <div class="dslField DSLForm__DslField--2BD7G Form__Field--OOguk form-group">
                                    <div>
                                      <label for="optionalFields.hireDate" class="control-label">Hire Date</label>
                                    </div>
                                    <div>
                                      <div class="form__formComponentWrapper--1it4c" data-qa-element-id="optionalFields.hireDate">
                                        <div class="DayPickerInput">
                                          <input placeholder="dd/mm/yyyy" class="DatePicker--Input" autocomplete="nope" value="">
                                        </div>
                                      </div>
                                      <span></span>
                                    </div>
                                  </div><!-- react-empty: 830 --><!-- react-empty: 831 --><!-- react-empty: 832 --></section>
                                </div>
                              </section>
                            </div>
                          </main>
                        </section>
                        <section class="Section__Section--135lt Section__BorderedSection--2Q2zZ">
                          <header class="Section__Header--100R1">
                            <h3 class="Section__Title--dnZyp">Contact Data</h3>
                            <i class="fa Section__Toggle--3mQrH fa-chevron-down"></i>
                          </header>
                          <main class="Section__Items--1CIJT FormLayout__Section--VSZOD dslGroup">
                            <div class="dslField DSLForm__DslField--2BD7G Form__Field--OOguk form__formFieldRequired--2rJuy form-group">
                              <div>
                                <label for="email" class="control-label">E-Mail</label>
                              </div>
                              <div>
                                <div class="form__formComponentWrapper--1it4c">
                                  <input type="text" name="email" value="" label="E-Mail" novalidate="" data-qa-element-id="email" id="email" class="form-control">
                                </div>
                                <span></span>
                              </div>
                            </div>
                            <!--<div class="dslField DSLForm__DslField--2BD7G Form__Field--OOguk form-group">
                              <div>
                                <label for="skype" class="control-label">Skype</label>
                              </div>
                              <div>
                                <div class="form__formComponentWrapper--1it4c">
                                  <input type="text" name="skype" value="" label="Skype" novalidate="" data-qa-element-id="skype" id="skype" class="form-control">
                                </div>
                                <span></span>
                              </div>
                            </div> -->
                            <div class="Form__PhoneSection--3khBO Form__SectionContainer--1uXxA">
                              <section class="dslField DSLForm__DslField--2BD7G Form__PhoneContainer--bxc2p">
                                <div class="Form__PhoneRegionCodeSelect--1LyXs Form__Field--OOguk form-group">
                                  <div>
                                    <label for="mobile.regionCode" class="control-label">Phone Number</label>
                                  </div>
                                  <div>
                                    <div class="form__formComponentWrapper--1it4c">
                                      <div data-qa-element-id="mobile.regionCode">
                                        <div class="Select form__select--3doYB Select--single is-searchable has-value">
                                          <div class="Select-control">
                                            <span class="Select-multi-value-wrapper" id="react-select-6--value">
                                            <div class="Select-value">
                                              <span class="Select-value-label" role="option" aria-selected="true" id="react-select-6--value-item">PH + 63</span>
                                            </div>
                                            <div class="Select-input" style="display: inline-block;">
                                              <input role="combobox" aria-expanded="false" aria-owns="" aria-haspopup="false" aria-activedescendant="react-select-6--value" value="" style="width: 19px; box-sizing: content-box;">
                                              <div style="position: absolute; top: 0px; left: 0px; visibility: hidden; height: 0px; overflow: scroll; white-space: pre;"></div>
                                            </div>
                                          </span>
                                          <span class="Select-arrow-zone">
                                            <span class="Select-arrow"></span>
                                          </span>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <span></span>
                                </div>
                              </div>
                              <div class="Form__PhoneNumberInput--27V8_ Form__Field--OOguk form-group">
                                  <div>
                                    <label for="mobile.number" class="control-label"></label>
                                  </div>
                                    <div>
                                      <div class="form__formComponentWrapper--1it4c">
                                        <input type="tel" name="mobile.number" value="" novalidate="" data-qa-element-id="mobile.number" id="mobile.number" class="form-control">
                                      </div>
                                      <span></span>
                                    </div>
                                  </div>
                                </section>
                              </div>
                            </main>
                          </section>
                          <section class="Section__Section--135lt Section__BorderedSection--2Q2zZ">
                            <header class="Section__Header--100R1">
                              <h3 class="Section__Title--dnZyp">Login Data</h3>
                              <i class="fa Section__Toggle--3mQrH fa-chevron-down"></i>
                            </header>
                            <main class="Section__Items--1CIJT FormLayout__Section--VSZOD FormLayout__SectionVertical--araJo dslGroup">
                              <div class="dslField DSLForm__DslField--2BD7G Form__Field--OOguk form__formFieldRequired--2rJuy form-group">
                                <div>
                                  <label for="username" class="control-label">Username</label>
                                </div>
                                <div>
                                  <div class="form__formComponentWrapper--1it4c">
                                    <input type="text" name="username" value="" label="Username" novalidate="" data-qa-element-id="username" id="username" class="form-control" values="[object Object]"></div>
                                    <span></span>
                                  </div>
                                </div>
                                <div class="dslField DSLForm__DslField--2BD7G DSLForm__UserPassword--Ph6of Form__Field--OOguk form__formFieldRequired--2rJuy form-group">
                                  <div>
                                    <label for="password" class="control-label">Password</label>
                                  </div>
                                  <div>
                                    <div class="form__formComponentWrapper--1it4c">
                                      <input type="password" name="password" value="" label="Password" novalidate="" data-qa-element-id="password" id="password" class="form-control">
                                    </div>
                                    <span></span>
                                  </div>
                                </div>
                                <div class="dslField DSLForm__DslField--2BD7G Form__Field--OOguk form__formFieldRequired--2rJuy form-group">
                                  <div>
                                    <label for="confirmPassword" class="control-label">Confirm Password</label>
                                  </div>
                                  <div>
                                    <div class="form__formComponentWrapper--1it4c">
                                      <input type="password" name="confirmPassword" value="" label="Confirm Password" novalidate="" data-qa-element-id="confirmPassword" id="confirmPassword" class="form-control" values="[object Object]">
                                    </div>
                                      <span></span>
                                    </div>
                                  </div>
                                  <div class="dslField DSLForm__DslField--2BD7G Form__Field--OOguk form__checkboxBlock--EG9I4 form-group">
                                    <div>
                                      <div class="checkbox">
                                        <label title="">
                                          <input type="checkbox" name="changePasswordOnNextLogin" value="true" novalidate="" data-qa-element-id="changePasswordOnNextLogin" values="[object Object]">
                                          <span>Change Password on Next Login</span>
                                        </label>
                                      </div>
                                      <span></span>
                                    </div>
                                  </div>
                                </main>
                              </section>
                              <section class="Section__Section--135lt Section__BorderedSection--2Q2zZ">
                                <header class="Section__Header--100R1">
                                  <h3 class="Section__Title--dnZyp">Privileges</h3>
                                  <i class="fa Section__Toggle--3mQrH fa-chevron-down"></i>
                                </header>
                                <main class="Section__Items--1CIJT FormLayout__Section--VSZOD dslGroup">
                                  <div class="dslField DSLForm__DslField--2BD7G Form__Field--OOguk form__formFieldRequired--2rJuy form-group">
                                    <div>
                                      <label for="groupIDs" class="control-label">Roles</label>
                                    </div>
                                    <div>
                                      <div class="form__formComponentWrapper--1it4c">
                                        <div data-qa-element-id="groupIDs">
                                          <div class="Select form__select--3doYB Select--multi is-clearable is-searchable">
                                            <div class="Select-control">
                                              <span class="Select-multi-value-wrapper" id="react-select-7--value">
                                                <div class="Select-placeholder">Select...</div>
                                                <div class="Select-input" style="display: inline-block;">
                                                  <input role="combobox" aria-expanded="false" aria-owns="" aria-haspopup="false" aria-activedescendant="react-select-7--value" value="" style="width: 19px; box-sizing: content-box;">
                                                  <div style="position: absolute; top: 0px; left: 0px; visibility: hidden; height: 0px; overflow: scroll; white-space: pre;">
                                                  </div>
                                                </div>
                                              </span>
                                              <span class="Select-arrow-zone">
                                                <span class="Select-arrow"></span>
                                              </span>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <span></span>
                                    </div>
                                  </div>
                                  <div class="dslField DSLForm__DslField--2BD7G Form__Field--OOguk form__formFieldRequired--2rJuy form__checkboxBlock--EG9I4 form-group">
                                    <div>
                                      <div class="checkbox">
                                        <label title="">
                                          <input type="checkbox" name="isCreditOfficer" value="false" novalidate="" data-qa-element-id="isCreditOfficer" values="[object Object]">
                                          <span>Credit Officer</span>
                                        </label>
                                      </div>
                                      <span>
                                      </span>
                                    </div>
                                  </div>
                                  <div class="Form__LTL--YHYTv">
                                    <div class="dslField DSLForm__DslField--2BD7G Form__Field--OOguk form__formFieldRequired--2rJuy">
                                      <div class="form__formFieldRequired--2rJuy form-group">
                                        <div>
                                          <label for="ltl.levelType" class="control-label">Level Type</label>
                                        </div>
                                      <div>
                                        <div class="form__formComponentWrapper--1it4c">
                                          <div data-qa-element-id="ltl.levelType">
                                            <div class="Select form__select--3doYB Select--single is-clearable is-searchable has-value">
                                              <div class="Select-control">
                                                <span class="Select-multi-value-wrapper" id="react-select-8--value">
                                                  <div class="Select-value">
                                                    <span class="Select-value-label" role="option" aria-selected="true" id="react-select-8--value-item">Branch</span>
                                                  </div>
                                                  <div class="Select-input" style="display: inline-block;">
                                                    <input role="combobox" aria-expanded="false" aria-owns="" aria-haspopup="false" aria-activedescendant="react-select-8--value" value="" style="width: 19px; box-sizing: content-box;">
                                                    <div style="position: absolute; top: 0px; left: 0px; visibility: hidden; height: 0px; overflow: scroll; white-space: pre;">
                                                    </div>
                                                  </div>
                                                </span>
                                                <span class="Select-clear-zone" title="Clear value" aria-label="Clear value">
                                                  <span class="Select-clear">×</span>
                                                </span>
                                                <span class="Select-arrow-zone">
                                                  <span class="Select-arrow"></span>
                                                </span>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <span></span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="dslField DSLForm__DslField--2BD7G Form__Field--OOguk form__formFieldRequired--2rJuy">
                                    <div class="form__formFieldRequired--2rJuy form-group">
                                      <div>
                                        <label for="ltl.level" class="control-label">Level</label>
                                      </div>
                                      <div>
                                        <div class="form__formComponentWrapper--1it4c">
                                          <div data-qa-element-id="ltl.level">
                                            <div class="Select form__select--3doYB Select--single is-clearable is-searchable has-value">
                                              <div class="Select-control">
                                                <span class="Select-multi-value-wrapper" id="react-select-9--value">
                                                  <div class="Select-value">
                                                    <span class="Select-value-label" role="option" aria-selected="true" id="react-select-9--value-item">Main Office</span>
                                                  </div>
                                                  <div class="Select-input" style="display: inline-block;">
                                                    <input role="combobox" aria-expanded="false" aria-owns="" aria-haspopup="false" aria-activedescendant="react-select-9--value" value="" style="width: 19px; box-sizing: content-box;">
                                                    <div style="position: absolute; top: 0px; left: 0px; visibility: hidden; height: 0px; overflow: scroll; white-space: pre;">
                                                    </div>
                                                  </div>
                                                </span>
                                                <span class="Select-clear-zone" title="Clear value" aria-label="Clear value">
                                                  <span class="Select-clear">×</span>
                                                </span>
                                                <span class="Select-arrow-zone">
                                                  <span class="Select-arrow"></span>
                                                </span>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <span></span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </main>
                            </section>
                            <section class="Section__Section--135lt Section__BorderedSection--2Q2zZ">
                              <header class="Section__Header--100R1">
                                <h3 class="Section__Title--dnZyp">Additional</h3>
                                <i class="fa Section__Toggle--3mQrH fa-chevron-down"></i>
                              </header>
                              <main class="Section__Items--1CIJT FormLayout__Section--VSZOD dslGroup">
                                <div class="dslField DSLForm__DslField--2BD7G DSLForm__DslFieldWide--2zNk0 Form__Field--OOguk form-group">
                                  <div>
                                    <label for="notes" class="control-label">Notes</label>
                                  </div>
                                  <div>
                                    <textarea name="notes" label="Notes" novalidate="" data-qa-element-id="notes" id="notes" class="form__formComponentWrapper--1it4c form-control"></textarea>
                                    <span></span>
                                  </div>
                                </div>
                              </main>
                            </section>
                          </section>
                        </div>
                      </main>
                      <span></span>
                      <section class="Form__Buttons--3qxxX">
                        <button type="submit" class="Form__Button--3lMwz btn btn-primary">Submit</button>
                        <button type="button" class="Form__Button--3lMwz btn btn-default">Cancel</button>
                      </section>
                    </form>
                  </div>
                </div>

  </div>


</html>
