#!/bin/bash

BASE_URL="http://localhost:8080/v1"

# 1. Login (Assuming user exists or shield setup created one)
# Note: You need to create a user first or use an existing one.
# For this test, we assume 'admin@oralis.com' / 'password'

echo "Testing Authenticaiton..."
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"email": "contato@fabianocardoso.com", "password": "password"}')

TOKEN=$(echo $LOGIN_RESPONSE | grep -o '"token":"[^"]*' | cut -d'"' -f4)

if [ -z "$TOKEN" ]; then
  echo "Login failed. Response: $LOGIN_RESPONSE"
  # Continue to test public endpoints anyway? No, most are protected.
  # exit 1 
else
  echo "Login successful. Token: $TOKEN"
fi

# 2. List Pacientes
echo "Testing List Pacientes..."
curl -s -X GET "$BASE_URL/pacientes" \
  -H "Authorization: Bearer $TOKEN"

# 3. Create Paciente
echo "\nTesting Create Paciente..."
curl -s -X POST "$BASE_URL/pacientes" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "paciente": {
        "nome_completo": "Test Paciente",
        "data_nascimento": "2020-01-01",
        "sexo": "M",
        "nome_mae": "Test Mae"
    },
    "responsaveis": []
  }'

# 4. Check Dashboard
echo "\nTesting Dashboard..."
curl -s -X GET "$BASE_URL/dashboard/indicadores" \
  -H "Authorization: Bearer $TOKEN"

echo "\nDone."
