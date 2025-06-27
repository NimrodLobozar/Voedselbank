<!-- From Uiverse.io by ElSombrero2 --> 
<div class="card">
  <div class="content">
    <div class="back">
      <div class="back-content">
        <svg stroke="#ffffff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="50px" width="50px" fill="#ffffff">
          <g stroke-width="0" id="SVGRepo_bgCarrier"></g>
          <g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g>
          <g id="SVGRepo_iconCarrier">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
          </g>
        </svg>
        <strong>{{ $customer->full_name }}</strong>
      </div>
    </div>
    <div class="front">
      <div class="img">
        <div class="circle"></div>
        <div class="circle" id="right"></div>
        <div class="circle" id="bottom"></div>
      </div>

      <div class="front-content">
        <small class="badge">{{ $customer->is_actief ? 'Actief' : 'Inactief' }}</small>
        <div class="description">
          <div class="title">
            <p class="title">
              <strong>{{ $customer->full_name }}</strong>
            </p>
            <div class="actions">
              <a href="{{ route('customers.show', $customer->id) }}" class="action-btn view-btn" title="Bekijk">👁</a>
              <a href="{{ route('customers.edit', $customer->id) }}" class="action-btn edit-btn" title="Bewerk">✏</a>
            </div>
          </div>
          <div class="card-details">
            <p><span>Adres:</span> {{ Str::limit($customer->full_address, 25) }}</p>
            <p><span>Huishoud:</span> {{ $customer->household_size }} personen</p>
          </div>
          <p class="card-footer">
            {{ $customer->birth_date ? \Carbon\Carbon::parse($customer->birth_date)->format('d-m-Y') : 'Geboortedatum onbekend' }}
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* From Uiverse.io by ElSombrero2 */ 
.card {
  overflow: visible;
  width: 190px;
  height: 254px;
}

.content {
  width: 100%;
  height: 100%;
  transform-style: preserve-3d;
  transition: transform 300ms;
  box-shadow: 0px 0px 10px 1px #000000ee;
  border-radius: 5px;
}

.front, .back {
  background-color: #151515;
  position: absolute;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
  border-radius: 5px;
  overflow: hidden;
}

.back {
  width: 100%;
  height: 100%;
  justify-content: center;
  display: flex;
  align-items: center;
  overflow: hidden;
}

.back::before {
  position: absolute;
  content: ' ';
  display: block;
  width: 160px;
  height: 160%;
  background: linear-gradient(90deg, transparent, #ff9966, #ff9966, #ff9966, #ff9966, transparent);
  animation: rotation_481 5000ms infinite linear;
}

.back-content {
  position: absolute;
  width: 99%;
  height: 99%;
  background-color: #151515;
  border-radius: 5px;
  color: white;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 30px;
}

.card:hover .content {
  transform: rotateY(180deg);
}

@keyframes rotation_481 {
  0% {
    transform: rotateZ(0deg);
  }
  100% {
    transform: rotateZ(360deg);
  }
}

.front {
  transform: rotateY(180deg);
  color: white;
}

.front .front-content {
  position: absolute;
  width: 100%;
  height: 100%;
  padding: 10px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.front-content .badge {
  background-color: #00000055;
  padding: 2px 10px;
  border-radius: 10px;
  backdrop-filter: blur(2px);
  width: fit-content;
}

.description {
  box-shadow: 0px 0px 10px 5px #00000088;
  width: 100%;
  padding: 10px;
  background-color: #00000099;
  backdrop-filter: blur(5px);
  border-radius: 5px;
}

.title {
  font-size: 11px;
  max-width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.title p {
  width: 70%;
}

.actions {
  display: flex;
  gap: 5px;
}

.action-btn {
  color: white;
  text-decoration: none;
  font-size: 12px;
  padding: 2px 4px;
  border-radius: 3px;
  transition: background-color 0.2s;
}

.view-btn:hover {
  background-color: #0066cc;
}

.edit-btn:hover {
  background-color: #cc9900;
}

.card-details {
  margin: 8px 0;
  font-size: 9px;
}

.card-details p {
  margin: 2px 0;
  line-height: 1.3;
}

.card-details span {
  color: #ffffff88;
  font-weight: bold;
}

.card-footer {
  color: #ffffff88;
  margin-top: 5px;
  font-size: 8px;
}

.front .img {
  position: absolute;
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}

.circle {
  width: 90px;
  height: 90px;
  border-radius: 50%;
  background-color: #ffbb66;
  position: relative;
  filter: blur(15px);
  animation: floating 2600ms infinite linear;
}

#bottom {
  background-color: #ff8866;
  left: 50px;
  top: 0px;
  width: 150px;
  height: 150px;
  animation-delay: -800ms;
}

#right {
  background-color: #ff2233;
  left: 160px;
  top: -80px;
  width: 30px;
  height: 30px;
  animation-delay: -1800ms;
}

@keyframes floating {
  0% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(10px);
  }
  100% {
    transform: translateY(0px);
  }
}
</style>
