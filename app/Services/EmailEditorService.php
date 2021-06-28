<?php

namespace App\Services;

use App\EmailEditor;

class EmailEditorService
{
    public function selectBy($dir_name)
    {
        $query = EmailEditor::where('dir_name', $dir_name);
        return $query->first();
    }
}