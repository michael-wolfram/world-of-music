<?php
namespace App\Console;

class ConsoleColorFormatter {

  private $_defaultColor = "\e[0m";

  private $_successColor = "\e[1;32m";

  private $_warningColor = "\e[1;33m";

  private $_errorColor = "\e[1;31m";

  public function showSuccessMessage(string $message) : string {
    return $this->_successColor.'[success] '.$message.$this->_defaultColor;
  }

  public function showWarningMessage(string $message) : string {
    return $this->_warningColor.'[warning] '.$message.$this->_defaultColor;
  }

  public function showErrorMessage(string $message) : string {
    return $this->_errorColor.'[error] '.$message.$this->_defaultColor;
  }
}