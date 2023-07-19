<?php

namespace App\CustomRules;

class MenuRules
{
  public function nama_menu_duplikat($value, string &$error = null): bool
  {
    $menuModel = model(App\Models\MenuModel::class);
    $count = $menuModel->builder()
      ->where('LOWER(TRIM(nama_menu))', strtolower(trim($value)))
      ->countAllResults();
    if ($count > 0) {
      $error = "$value sudah terdaftar pada nama menu";
      return false;
    }
    return true;
  }
}
