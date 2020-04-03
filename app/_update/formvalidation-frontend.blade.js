
        function submitForm() {
            let isDataValid = performDataValidation();

            if (isDataValid) {
                fetch('{{ url('') }}' + `/customer/api/apply`, {
                    method: 'POST', // or 'PUT'
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(networkRequest)
                    })
                    .then((response) => { return response.json(); })
                    .then((res) => { 
                        if (res.status === 'success') {
                            location.reload();
                        }
                    })
                    .catch((error) => {
                        console.log(['err', error]);
                    });
            }
        }

        function performDataValidation() {
            if (!itemOptions.value) {
                showAlert('Item must not be empty!', 'alert-danger');
                return false;
            }

            if (!monthOptions.value) {
                showAlert('Installment month option of item must not be empty!', 'alert-danger');
                return false;
            }

            if (!radioIndividualApplicant.checked && !radioSelfEmployed.checked) {
                showAlert('Applicant type must not be empty!', 'alert-danger');
                return false;
            }

            if (!nameOfApplicant.value) {
                showAlert('Name of applicant must not be empty!', 'alert-danger');
                return false;
            }
            
            if (!icNumber.value) {
                showAlert('IC Number must not be empty!', 'alert-danger');
                return false;
            }

            if (!contactOneOfApplicant.value) {
                showAlert('Contact one of applicant must not be empty!', 'alert-danger');
                return false;
            }

            if (!emailOfApplicant.value) {
                showAlert('Email of applicant must not be empty!', 'alert-danger');
                return false;
            }

            if (!addressOne.value) {
                showAlert('Address one of applicant must not be empty!', 'alert-danger');
                return false;
            }

            if (!postcode.value) {
                showAlert('Postcode must not be empty!', 'alert-danger');
                return false;
            }

            if (!countryOptions.value) {
                showAlert('Country must not be empty!', 'alert-danger');
                return false;
            }

            if (!stateOptions.value) {
                showAlert('State must not be empty!', 'alert-danger');
                return false;
            }

            if (!cityOptions.value) {
                showAlert('City must not be empty!', 'alert-danger');
                return false;
            }

            if (radioIndividualApplicant.checked) {
                if (!individualApplicantIC.value) {
                    showAlert('Individual applicant IC must be submitted!', 'alert-danger');
                    return false;
                }

                if (!individualApplicantIncome.value) {
                    showAlert('Individual applicant Income Proof must be submitted!', 'alert-danger');
                    return false;
                }

                if (!individualApplicantBankStatement.value) {
                    showAlert('Individual applicant Bank Statement must be submitted!', 'alert-danger');
                    return false;
                }
            } else if (radioSelfEmployed.checked) {
                if (!selfEmployedIC.value) {
                    showAlert('Self Employed applicant IC must be submitted!', 'alert-danger');
                    return false;
                }

                if (!selfEmployedForm.value) {
                    showAlert('Self Employed applicant company form must be submitted!', 'alert-danger');
                    return false;
                }

                if (!selfEmployedBankStatement.value) {
                    showAlert('Self Employed applicant Bank Statement must be submitted!', 'alert-danger');
                    return false;
                }
            }

            if (!tandcitsu.checked || !tandcctos.checked) {
                showAlert('Terms and condition must be ticked!', 'alert-danger');
                return false;
            }

            return true;
        }