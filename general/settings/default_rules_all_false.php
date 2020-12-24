{
  "sistem":{
            "name":"Настройки системы",
            "alert":false,
            "rule":{
                "settings":{
                    "name":"settings",
                    "description":"Доступ к настройкам системы",
                    "value":false
                },
                "load":{
                    "name":"load",
                    "description":"Доступ к нагрузке системы",
                    "value":false
                },
                "loging":{
                    "name":"loging",
                    "description":"Доступ к логированию и ошибкам системы системы",
                    "value":false
                },
                "history":{
                    "name":"history",
                    "description":"Доступ к истории системы",
                    "value":false
                }
            }
  },
  "emploe":{
            "name":"Настройки пользователей",
            "alert":false,
            "rule":{
                  "view_all_users":{
                      "name":"view_all_emploe",
                      "description":"Доступ к просмотру всех пользователей системы",
                      "value":false
                  },
                  "add_new_users":{
                      "name":"add_new_users",
                      "description":"Доступ к добавлению новых пользователей в систему",
                      "value":false
                  },
                  "edit_role_users":{
                      "name":"edit_role_users",
                      "description":"Доступ к изменению",
                      "value":false
                  },
                  "delete_users":{
                      "name":"delete_users",
                      "description":"Доступ к удаению пользователей из системы",
                      "value":false
                  },
                  "view_one_user":{
                      "name":"view_one_user",
                      "description":"Просмотр одного пользователя",
                      "value":false
                  },
                  "add_new_role":{
                      "name":"add_new_role",
                      "description":"Доступ к добавлению новых ролей",
                      "value":false
                  },
                  "view_all_role":{
                      "name":"view_all_role",
                      "description":"Доступ к просмотру всех ролей",
                      "value":false
                  },
                  "delete_role":{
                      "name":"delete_role",
                      "description":"Доступ к удалению ролей",
                      "value":false
                  },
                  "edit_rules_role":{
                      "name":"edit_rules_role",
                      "description":"Доступ к редактированию прав ролей (этот раздел)",
                      "value":false
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
                        "value":false
                    },
                    "view_one_entity":{
                        "name":"view_one_entity",
                        "description":"Доступ к просмотру отдельной страницы юридического лица",
                        "value":false
                    },
                    "download_excel_one_entity":{
                        "name":"download_excel_one_entity",
                        "description":"Доступ к скачиванию файла excel по юридическому лицу",
                        "value":false
                    },
                    "download_pdf_one_entity":{
                        "name":"download_pdf_one_entity",
                        "description":"Доступ к скачиванию файла pdf по юридическому лицу",
                        "value":false
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
                          "value":false
                      },
                      "view_one_user":{
                          "name":"view_one_users",
                          "description":"Доступ к просмотру отдельной страницы физического лица",
                          "value":false
                      },
                      "download_excel_one_users":{
                          "name":"download_excel_one_users",
                          "description":"Доступ к скачиванию файла excel по физическому лицу",
                          "value":false
                      },
                      "download_pdf_one_users":{
                          "name":"download_pdf_one_users",
                          "description":"Доступ к скачиванию файла pdf по физическому лицу",
                          "value":false
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
                            "value":false
                        },
                        "view_one_event":{
                            "name":"view_one_users",
                            "description":"Доступ к просмотру отдельной страницы мероприятия",
                            "value":false
                        },
                        "download_excel_one_events":{
                            "name":"download_excel_one_events",
                            "description":"Доступ к скачиванию файла excel по мероприятию",
                            "value":false
                        },
                        "download_pdf_one_events":{
                            "name":"download_pdf_one_events",
                            "description":"Доступ к скачиванию файла pdf по мероприятию",
                            "value":false
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
                              "value":false
                          },
                          "download_excel_one_reports":{
                              "name":"download_excel_one_reports",
                              "description":"Доступ к скачиванию файла excel по отчету",
                              "value":false
                          },
                          "download_pdf_one_reports":{
                              "name":"download_pdf_one_reports",
                              "description":"Доступ к скачиванию файла pdf по отчету",
                              "value":false
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
                                  "value":false
                              },
                              "allin_canban":{
                                  "name":"allin_canban",
                                  "description":"Доступ к просмотру к общему канбану заявок",
                                  "value":false
                              },
                              "view_history":{
                                  "name":"view_history",
                                  "description":"Доступ к просмотру истории действий над заявками",
                                  "value":false
                              },
                              "view_history_tiket":{
                                  "name":"view_history",
                                  "description":"Доступ к просмотру истории действий с одной завкой",
                                  "value":false
                              },
                              "view_all_open_support_tikets":{
                                  "name":"view_all_open_support_tikets",
                                  "description":"Доступ к просмотру открытых заявок",
                                  "value":false
                              },
                              "view_all_close_support_tikets":{
                                  "name":"view_all_close_support_tikets",
                                  "description":"Доступ к просмотру закрытых заявок",
                                  "value":false
                              },
                              "view_all_work_support_tikets":{
                                  "name":"view_all_work_support_tikets",
                                  "description":"Доступ к просмотру заявок в работе",
                                  "value":false
                              },
                              "download_excel_one_reports":{
                                  "name":"download_excel_one_reports",
                                  "description":"Доступ к скачиванию файла excel по заявке",
                                  "value":false
                              },
                              "download_pdf_one_reports":{
                                  "name":"download_pdf_one_reports",
                                  "description":"Доступ к скачиванию файла pdf по заявке",
                                  "value":false
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
                                  "value":false
                              }
                        }
              }
}
