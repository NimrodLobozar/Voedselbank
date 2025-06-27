<!-- From Uiverse.io by andrew-demchenk0 --> 
<div class="pkg-wrapper">
  <div class="pkg-card">
    <input class="pkg-input" type="radio" name="card" value="basic">
    <span class="pkg-check"></span>
    <label class="pkg-label">
      <div class="pkg-title">BASIC</div>
      <div class="pkg-price">
        <span class="pkg-span">$</span>
        15
      </div>
    </label>
  </div>
  <div class="pkg-card">
    <input class="pkg-input" type="radio" name="card" value="standart">
    <span class="pkg-check"></span>
    <label class="pkg-label">
      <div class="pkg-title">STANDART</div>
      <div class="pkg-price">
        <span class="pkg-span">$</span>
        30
      </div>
    </label>
  </div>
  <div class="pkg-card">
    <input class="pkg-input" type="radio" name="card" value="premium">
    <span class="pkg-check"></span>
    <label class="pkg-label">
      <div class="pkg-title">PREMIUM</div>
      <div class="pkg-price">
        <span class="pkg-span">$</span>
        60
      </div>
    </label>
  </div>
</div>

<style>
  /* From Uiverse.io by andrew-demchenk0 - Modified with pkg- prefix */ 
.pkg-wrapper {
  position: relative;
  display: flex;
  flex-direction: row;
  gap: 10px;
}

.pkg-card {
  position: relative;
  width: 150px;
  height: 100px;
  background: #fff;
  border-radius: 10px;
  transition: all 0.3s;
}

.pkg-card:hover {
  transform: scale(1.05);
}

.pkg-input {
  position: relative;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  cursor: pointer;
  appearance: none;
  border: 1px solid #e5e5e5;
  border-radius: 10px;
  z-index: 10;
  box-shadow: 1px 1px 10px #aaaaaa,
              -1px -1px 10px #ffffff;
}

.pkg-input + .pkg-check::before {
  content: "";
  position: absolute;
  top: 15px;
  right: 15px;
  width: 16px;
  height: 16px;
  border: 2px solid #d0d0d0;
  border-radius: 50%;
  background-color: #E8E8E8;
}

.pkg-input:checked + .pkg-check::after {
  content: '';
  position: absolute;
  top: 19px;
  right: 19px;
  width: 12px;
  height: 12px;
  background-color: rgba(255,0,0,0.7);
  border-radius: 50%;
}

.pkg-input[value="standart"]:checked + .pkg-check::after {
  background-color: rgba(255,165,0,0.7);
}

.pkg-input[value="premium"]:checked + .pkg-check::after {
  background-color: rgba(0,128,0,0.7);
}

.pkg-input[value="basic"]:checked {
  border: 1.5px solid rgba(255,0,0,0.7);
}

.pkg-input[value="standart"]:checked {
  border: 1.5px solid rgba(255,165,0,0.7);
}

.pkg-input[value="premium"]:checked {
  border: 1.5px solid rgba(0,128,0,0.7);
}

.pkg-label {
  color: #323232;
  position: absolute;
  top: 0;
  left: 0;
  z-index: 0;
}

.pkg-label .pkg-title {
  margin: 15px 0 0 15px;
  font-weight: 900;
  font-size: 15px;
  letter-spacing: 1.5px;
}

.pkg-label .pkg-price {
  margin: 20px 0 0 15px;
  font-size: 20px;
  font-weight: 900;
}

.pkg-label .pkg-span {
  color: gray;
  font-weight: 700;
  font-size: 15px;
}

</style>