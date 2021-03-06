{
  "sistem":{
            "name":"Настройки системы",
            "alert":true,
            "rule":{
                "settings":{
                    "name":"settings",
                    "description":"Доступ к настройкам системы",
                    "value":true
                },
                "load":{
                    "name":"load",
                    "description":"Доступ к нагрузке системы",
                    "value":true
                },
                "loging":{
                    "name":"loging",
                    "description":"Доступ к логированию и ошибкам системы системы",
                    "value":true
                },
                "history":{
                    "name":"history",
                    "description":"Доступ к истории системы",
                    "value":true 
                }
            }
  },
  "emploe":{
            "name":"Настройки пользователей",
            "alert":true,
            "rule":{
                  "view_all_users":{
                      "name":"view_all_emploe",
                      "description":"Доступ к просмотру всех пользователей системы",
                      "value":true
                  },
                  "add_new_users":{
                      "name":"add_new_users",
                      "description":"Доступ к добавлению новых пользователей в систему",
                      "value":true
                  },
                  "edit_role_users":{
                      "name":"edit_role_users",
                      "description":"Доступ к изменению",
                      "value":true
                  },
                  "delete_users":{
                      "name":"delete_users",
                      "description":"Доступ к удаению пользователей из системы",
                      "value":true
                  },
                  "view_one_user":{
                      "name":"view_one_user",
                      "description":"Просмотр одного пользователя",
                      "value":true
                  },
                  "add_new_role":{
                      "name":"add_new_role",
                      "description":"Доступ к добавлению новых ролей",
                      "value":true
                  },
                  "view_all_role":{
                      "name":"view_all_role",
                      "description":"Доступ к просмотру всех ролей",
                      "value":true
                  },
                  "delete_role":{
                      "name":"delete_role",
                      "description":"Доступ к удалению ролей",
                      "value":true
                  },
                  "edit_rules_role":{
                      "name":"edit_rules_role",
                      "description":"Доступ к редактированию прав ролей (этот раздел)",
                      "value":true
                  }
            }
    },
    "entity":{
              "name":"Данные юр. лиц",
              "alert":false,
              "rule":{
                    "view_all_entity":{
                        "name":"view_all_entity",
                        "description":"Доступ к просмотру всех юридических лиц системы",
                        "value":true
                    },
                    "view_one_entity":{
                        "name":"view_one_entity",
                        "description":"Доступ к просмотру отдельной страницы юридического лица",
                        "value":true
                    },
                    "download_excel_one_entity":{
                        "name":"download_excel_one_entity",
                        "description":"Доступ к скачиванию файла excel по юридическому лицу",
                        "value":true
                    },
                    "download_pdf_one_entity":{
                        "name":"download_pdf_one_entity",
                        "description":"Доступ к скачиванию файла pdf по юридическому лицу",
                        "value":true
                    }
              }
      },
      "users":{
                "name":"Данные физ. лиц",
                "alert":false,
                "rule":{
                      "view_all_users":{
                          "name":"view_all_users",
                          "description":"Доступ к просмотру всех физических лиц системы",
                          "value":true
                      },
                      "view_one_user":{
                          "name":"view_one_users",
                          "description":"Доступ к просмотру отдельной страницы физического лица",
                          "value":true
                      },
                      "download_excel_one_users":{
                          "name":"download_excel_one_users",
                          "description":"Доступ к скачиванию файла excel по физическому лицу",
                          "value":true
                      },
                      "download_pdf_one_users":{
                          "name":"download_pdf_one_users",
                          "description":"Доступ к скачиванию файла pdf по физическому лицу",
                          "value":true
                      }
              }
        },
        "events":{
                "name":"Данные мероприятий",
                "alert":false,
                "rule":{
                        "view_all_events":{
                            "name":"view_all_events",
                            "description":"Доступ к просмотру всех мероприятий",
                            "value":true
                        },
                        "view_one_event":{
                            "name":"view_one_users",
                            "description":"Доступ к просмотру отдельной страницы мероприятия",
                            "value":true
                        },
                        "download_excel_one_events":{
                            "name":"download_excel_one_events",
                            "description":"Доступ к скачиванию файла excel по мероприятию",
                            "value":true
                        },
                        "download_pdf_one_events":{
                            "name":"download_pdf_one_events",
                            "description":"Доступ к скачиванию файла pdf по мероприятию",
                            "value":true
                        }
                }
          },
          "reports":{
                    "name":"Отчеты",
                    "alert":false,
                    "rule":{
                          "view_all_reports":{
                              "name":"view_all_reports",
                              "description":"Доступ к отчетам",
                              "value":true
                          },
                          "download_excel_one_reports":{
                              "name":"download_excel_one_reports",
                              "description":"Доступ к скачиванию файла excel по отчету",
                              "value":true
                          },
                          "download_pdf_one_reports":{
                              "name":"download_pdf_one_reports",
                              "description":"Доступ к скачиванию файла pdf по отчету",
                              "value":true
                          }
                    }
            },
            "support":{
                      "name":"Тех. поддержка",
                      "alert":false,
                      "rule":{
                              "view_all_support_tikets":{
                                  "name":"view_all_support_tikets",
                                  "description":"Доступ к просмотру всех заявок",
                                  "value":true
                              },
                              "allin_canban":{
                                  "name":"allin_canban",
                                  "description":"Доступ к просмотру к общему канбану заявок",
                                  "value":true
                              },
                              "view_history":{
                                  "name":"view_history",
                                  "description":"Доступ к просмотру истории действий над заявками",
                                  "value":true
                              },
                              "view_history_tiket":{
                                  "name":"view_history",
                                  "description":"Доступ к просмотру истории действий с одной завкой",
                                  "value":true
                              },
                              "view_all_open_support_tikets":{
                                  "name":"view_all_open_support_tikets",
                                  "description":"Доступ к просмотру открытых заявок",
                                  "value":true
                              },
                              "view_all_close_support_tikets":{
                                  "name":"view_all_close_support_tikets",
                                  "description":"Доступ к просмотру закрытых заявок",
                                  "value":true
                              },
                              "view_all_work_support_tikets":{
                                  "name":"view_all_work_support_tikets",
                                  "description":"Доступ к просмотру заявок в работе",
                                  "value":true
                              },
                              "download_excel_one_reports":{
                                  "name":"download_excel_one_reports",
                                  "description":"Доступ к скачиванию файла excel по заявке",
                                  "value":true
                              },
                              "download_pdf_one_reports":{
                                  "name":"download_pdf_one_reports",
                                  "description":"Доступ к скачиванию файла pdf по заявке",
                                  "value":true
                              }
                     }
              },
              "dashboard":{
                        "name":"Дашборд",
                        "alert":false,
                        "rule":{
                              "view_dashboard":{
                                  "name":"view_dashboard",
                                  "description":"Доступ к просмотру дашборда",
                                  "value":true
                              }
                        }
              }
}
