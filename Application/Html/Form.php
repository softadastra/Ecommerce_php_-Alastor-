<?php

namespace Softadastra\Application\Html;

class Form
{
    private $formCode = '';

    public function create()
    {
        return $this->formCode;
    }

    public static function validate(array $form, array $champs)
    {
        foreach ($champs as $champ) {
            if (!isset($form[$champ]) || empty($form[$champ])) {
                return false;
            }
        }
        return true;
    }

    public function ajoutAttributs(array $attributs)
    {
        $str = '';
        $couts = ['checked', 'disabled', 'readonly', 'multiple', 'required', 'autofocus', 'novalidate', 'formovalidate'];

        foreach ($attributs as $attribut => $value) {
            if (in_array(
                $attribut,
                $couts
            ) && $value == true) {
                $str .= " $attribut";
            } else {
                $str .= " $attribut='$value'";
            }
        }

        return $str;
    }

    public function debutForm(string $methode = 'post', string $action = '#', array $attributs = [])
    {
        $this->formCode .= "<form action='$action' method='$methode' enctype='multipart/form-data' class='formulaire' id='uploadForm'";
        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs) . '>' : '>';

        return $this;
    }

    public function findForm()
    {
        $this->formCode .= '</form>';
        return $this;
    }

    public function ajoutLabelFor(string $for, string $texte, array $attributs = [])
    {

        $this->formCode .= "<label for='$for'>";

        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs) : '';

        $this->formCode .= "$texte</label>";

        return $this;
    }

    public function ajoutInput(string $type, string $nom, array $attributs = [])
    {
        $this->formCode .= "<input type='$type' name='$nom'";
        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs) . '>' : '>';
        return $this;
    }

    public function ajouteTextarea(string $nom, string $valeur = '', array $attributs = [])
    {

        $this->formCode .= "<textarea name='$nom'>";

        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs) : '';

        $this->formCode .= "$valeur</textarea>";

        return $this;
    }

    public function ajoutSelect(string $nom, array $options, array $attributs = [])
    {
        if (isset($attributs['multiple'])) {
            $nom .= '[]';
        }
        $this->formCode .= "<select name='$nom'";
        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs) . '>' : '>';
        $this->formCode .= "<option value='' disabled selected>Sélectionner</option>";
        $selectedValues = isset($attributs['value']) ? (array) $attributs['value'] : [];
        foreach ($options as $valeur => $texte) {
            $selected = '';
            if (in_array($valeur, $selectedValues)) {
                $selected = 'selected';
            }
            $this->formCode .= "<option value='$valeur' $selected>$texte</option>";
        }
        $this->formCode .= '</select>';
        return $this;
    }

    public function ajoutBouton(string $text, array $attributs = [])
    {
        $this->formCode .= '<button ';
        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs) : '';
        $this->formCode .= ">$text</button>";
        return $this;
    }

    public function ajoutImageMultiple()
    {
        $html = '<div class="image_product" style="margin: 10px 0px;">';
        $html .= '<h5 style="font-weight: 700;">Images :</h5>';
        $html .= '<input type="file" name="images[]" class="form-control" multiple>';
        $html .= '<div id="imagePreview"></div>';
        $html .= '</div>';
        $this->formCode .= $html;
        return $this;
    }
}
