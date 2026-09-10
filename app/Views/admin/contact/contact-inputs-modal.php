<?php
$contact = $contact ?? [];
?>
<div class="row mb-3 row-contact">
    <div class="col">
        <span class="fw-semibold text-decoration-underline"> Contact n° <?=$nbContacts?></span>
        <div class="row mb-3">
            <div class="col-6">
                <label class="form-label" for="phone_number<?=$nbContacts?>">Numéro de telephone</label>
                <input class="form-control" type="text" id="phone_number<?=$nbContacts?>" name="contacts[<?=$nbContacts?>][phone_number]" value="<?= old('contact.'
                    .$nbContacts.'.phone_number',esc($contact['phone_number']??''));?>" minlength="10" maxlength="10">
            </div>
            <div class="col-6">
                <label class="form-label" for="mail<?=$nbContacts?>">Adresse e-mail</label>
                <input class="form-control" type="text" id="mail<?=$nbContacts?>" name="contacts[<?=$nbContacts?>][mail]" value="<?= old('contact.'.$nbContacts.'.mail',esc($contact['mail']??''));?>">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label" for="details<?=$nbContacts?>">Détails du contact <span class="fw-lighter fst-italic">(optionnel, max. 255 caractères)</span></label>
                <textarea class="form-control" name="contacts[<?=$nbContacts?>][details]}" id="details<?=$nbContacts?>" rows="2" ><?= old('contact.' .$nbContacts.'.details',esc($contact['details']??''));?></textarea>
            </div>
            <div class="col-auto d-flex align-items-center">
                <span class="fs-4" id="delete-contact-<?=$nbContacts?>"><i class="fas fa-trash-alt text-danger delete-contact-button"></i></span>
            </div>
        </div>
    </div>

    <input type="hidden" class="contact-id-input" name="contacts[<?=$nbContacts?>][id]" value="<?= old('contact.'.$nbContacts.'.id',esc($contact['id']??''));
    ?>">
</div>
