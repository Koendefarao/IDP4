<div class="row">
    <div class="col-md-6">
        <h1>Login</h1>
        <br>
        <form method="post" action="<?= $this->link(array('controller' => 'main', 'action' => 'login')) ?>">
            <div class="form-group">
                <label for="login_username">Username</label>
                <input type="text" class="form-control" id="login_username" placeholder="Username" name="username">
            </div>
            <div class="form-group">
                <label for="login_password">Password</label>
                <input type="password" class="form-control" id="login_password" placeholder="Password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <div class="col-md-6">
        <h1>Register</h1>
        <br>
        <form method="post" action="<?= $this->link(array('action' => 'register', 'controller' => 'main')) ?>">
            <div class="form-group">
                <label for="register_username">Username</label>
                <input type="text" class="form-control" id="register_username" placeholder="Username" name="username">
            </div>
            <div class="form-group">
                <label for="register_password">Password</label>
                <input type="password" class="form-control" id="register_password" placeholder="Password" name="password">
            </div>
            <div class="form-group">
                <label for="register_first_name">First Name</label>
                <input  type="text" class="form-control" id="register_first_name" placeholder="First Name" name="first_name">
            </div>
            <div class="form-group">
                <label for="register_last_name">Last Name</label>
                <input  type="text" class="form-control" id="register_last_name" placeholder="Last Name" name="last_name">
            </div>
            <div class="form-group">
                <label for="register_email">Email</label>
                <input type="email" class="form-control" id="register_email" placeholder="Email" name="email">
            </div>
            <div class="form-group">
                <label for="register_city">City</label>
                <input type="text" class="form-control" id="register_city" placeholder="City" name="city">
            </div>
            <div class="form-group">
                <label for="register_address">Address</label>
                <input type="text" class="form-control" id="register_address" placeholder="Address" name="address">
            </div>
            <div class="form-group">
                <label for="register_postcode">Post Code</label>
                <input type="text" class="form-control" id="register_postcode" placeholder="Post Code" name="postcode">
            </div>
            <div class="form-group">
                <label for="register_iban">IBAN nr</label>
                <input type="text" class="form-control" id="register_iban" placeholder="Iban NR" name="iban_nr">
            </div>
            <div class="form-group">
                <label for="register_sub">Subscription</label>
                <select name="subscription" id="register_sub">
                    <option value="0">Geen</option>
                    <option value="1">Wel</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>