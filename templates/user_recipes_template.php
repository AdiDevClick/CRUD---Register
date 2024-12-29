<template id="user-recipes-template">
    <article class="item_container">
        <div class="item__image no-hover">
            <img class="js-img" src="{{img_path}}" alt="">
            <div class="js-youtube-player" id="UzRY3BsWFYg"></div>
        </div>
        <div class="item__body no-fade">
            <div class="item__title js-title">
                {{title}}
            </div>
        </div>
        <a class="file-uploader js-href" href="./recipes/read.php?id={{recipe_id}}">Voir votre recette</a>
        <div class="item__buttons">
            <div tabindex="-1">
                <img src="img/edit.svg" alt="" class="item__modify" name="modify" id="modify-{{id}}">
                <a href="./recipes/update_recipes.php?id={{recipe_id}}"></a>
            </div>

            <!-- <img src=" img/bin.svg" alt="" class="item__delete" name="delete" id="delete-{{id}}"> -->
            <svg class="item__delete" fill="#000000" width="25" height="25" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 400 480" xml:space="preserve">
                <!-- Poubelle en forme de V adouci avec traits épais -->
                <path d="M76.373,123.881 C80,130 96,420 100.373,420 L291.373,420 C295,420 311,130 315.373,123.881 Z" fill="gray" stroke="black" stroke-width="2" />
                <!-- Lignes verticales alignées et centrées comme justify-content: center avec traits épais -->
                <rect x="126.373" y="164.436" width="25.346" height="215.87" rx="10" ry="10" fill="black" stroke="black" stroke-width="2" />
                <rect x="181.373" y="164.436" width="25.347" height="215.87" rx="10" ry="10" fill="black" stroke="black" stroke-width="2" />
                <rect x="236.373" y="164.436" width="25.348" height="215.87" rx="10" ry="10" fill="black" stroke="black" stroke-width="2" />
                <!-- Couvercle entre-ouvert avec rectangle et traits épais -->
                <rect x="65" y="65" width="270" height="12" fill="gray" stroke="black" stroke-width="2" transform="rotate(-20, 150, 100)" />
                <!-- Poignée au centre du couvercle avec traits épais -->
                <rect x="200" y="16" width="20" height="15" fill="black" stroke="black" stroke-width="2" transform="rotate(-20, 220, 100)" />
                <!-- Papier froissé sous le couvercle avec traits épais -->
                <circle cx="310" cy="70" r="30" fill="white" stroke="gray" stroke-width="2" />
            </svg>
        </div>
    </article>
</template>