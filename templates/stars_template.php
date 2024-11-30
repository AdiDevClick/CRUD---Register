<div class="star">
    <label for="box-<?= $itemId ?>"></label>
    <input name="<?= $itemId ?>" id="box-<?= $itemId ?>" type="checkbox">
    <svg id="<?= $itemId ?>" width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Définit un masque de découpage -->
        <defs>
            <clipPath id="clip<?= '-' . $itemId ?>">
                <rect width="25" height="24" fill="none" style="width: <?= $width ?>;"></rect>
            </clipPath>
        </defs>
        <!-- Étoile en arrière-plan (pour remplir la partie restante si besoin) -->
        <path d="M6.325 22L7.95 14.975L2.5 10.25L9.7 9.625L12.5 3L15.3 9.625L22.5 10.25L17.05 14.975L18.675 22L12.5 18.275L6.325 22Z" fill="none" stroke="#FFA500" />
        <!-- Étoile principale avec découpage -->
        <path d="M6.325 22L7.95 14.975L2.5 10.25L9.7 9.625L12.5 3L15.3 9.625L22.5 10.25L17.05 14.975L18.675 22L12.5 18.275L6.325 22Z" fill="#FFA500" clip-path="url(#clip<?= strip_tags('-' . $itemId) ?>)" />
    </svg>
</div>