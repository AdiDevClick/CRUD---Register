
    <div class="alert-success">
    <section class="container">
        <div class="form-flex">
            <h1>Votre profil à bien été enregistré !</h1>
            <div class="card">
                <div class="card-body">
                    <h5>Rappel de vos informations :</h5>
                    <p><b>Votre nom</b> : <?php echo strip_tags($getDatas['username'])?></p>
                    <p><b>Votre email</b> : <?php echo strip_tags($getDatas['email'])?></p>
                    <p><b>Votre âge</b> : <?php echo strip_tags($getDatas['age'])?></p>
                    <p>Vous pouvez maintenant vous identifier, passez une bonne journée <?php echo strip_tags($getDatas['username'])?> !</p>
                </div>
            </div> 
        </div>
    </section>