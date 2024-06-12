<section class="section table-content">
    <table class="asign-table customer-table customer" id="customer_table">
        <thead>
            <tr>
                <th scope="col" class="code" id="code" width="10%">
                     <span class="content-start " data-bs-toggle="dropdown" aria-expanded="false" >Code</span>
                    <div class="dropdown-menu table-menu">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="recent" checked>
                            <label class="form-check-label content-style" for="flexRadioDefault1">
                                Recently Added
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="asc">
                            <label class="form-check-label content-style" for="flexRadioDefault2">
                                A to Z
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="desc">
                            <label class="form-check-label content-style" for="flexRadioDefault2">
                                Z to A
                            </label>
                        </div>
                    </div>
                </th>
                <!-- has_sort asc -->
                <th scope="col" width="19%" class="" id="sort_name" name="full_name" value="asc">Name</th>
                <th scope="col" width="12%" id="sort_city" name="city" value="asc">City</th>
                <th scope="col" width="14%" id="sort_mobile" name="mobile" value="asc">Mobile Number</th>
                <th scope="col" width="18%" id="sort_email" name="email" value="asc">Email ID</th>
                <th scope="col" width="12%" id="sort_status" name="status" value="asc">Status</th>
            </tr>
        </thead>
        <tbody id="customer">
            <tr>
                <td data-label="Code"></td>
                <td data-label="Name"></td>
                <td data-label="City"></td>
                <td data-label="Mobile"></td>
                <td data-label="Email"></td>
                <td data-label="Verification"></td>
            </tr>
        </tbody>
    </table>
</section>
