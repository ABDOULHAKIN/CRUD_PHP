<?php
session_start();
include 'includes/inc-top.php'; 
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <form action="/login-POST.php" method="POST">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Adresse email</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
                    <?php if(isset($_SESSION['errors']['email'])): ?>
                    <small class="text-danger"><?= $_SESSION['errors']['email'] ?></small>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password">
                    <?php if(isset($_SESSION['errors']['password'])): ?>
                    <small class="text-danger"><?= $_SESSION['errors']['password'] ?></small>
                    <?php endif; ?>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div>
                <input type="submit" name="submit" value="Envoyer" class="btn btn-primary">
            </form>

            <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger mt-4">
                <p class="m-0"><?= $_SESSION['error'] ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'includes/inc-bottom.php'; ?>