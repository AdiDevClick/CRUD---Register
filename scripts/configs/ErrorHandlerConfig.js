////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////
            /**
             * ERROR HANDLER CONFIG FILE
             */
////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////
                    /** FORM */

/** @type {String} Configurate form.id element to avoid checking */
export const formIDToAvoidChecking = 'search-form'

/** @type {String} Configurate form submit button ID to target and listen */
export const formButton = '#submit'

////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////
                    /** MESSAGES */

/**  @type {String} Configurate invalid email error message */
export const invalidEmailMessage = `Votre email est invalide 
                exemple valide : monEmail@mail.fr`

/**  @type {String} Configurate password invalid error message */
export const invalidPwMessage = 'Vos mots de passes ne sont pas identiques'

/**  @type {String} Configurate no space allowed error message */
export const noSpaceAllowedMessage = 'Veuillez ne pas utiliser d\'espace'

/**  @type {String} Configurate only integer allowed error message */
export const notANumberError = 'Seuls les nombres sont autorisés'

/**  @type {String} Configurate empty inputs error message */
export const emptyAlert = 'Un ou plusieurs champs doivent être renseignés'

////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////
                /** INPUTS TO LISTEN TO */

/**  @type {String} Configurate elements to be checked */
export const inputsToListen = 'input, textarea'

/**  @type {String} Configurate elements that should be INTEGERS only */
export const thisInputShouldBeInt = '#persons, #total_time, #resting_time, #oven_time'

////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////
                    /** REGEX */

/**  @type {RegExp} Configurate which characters are allowed */
export const allowedSpecialChars = new RegExp('^[\\w\\s,.:;_?\'!\\"*()~&éèêëàâäôöûüùçÀ-]+$')

/**  @type {RegExp} Configurate Email : Can only be email@email.co */
export const emailInputRegex = new RegExp("([a-z0-9A-Z._-]+)@([a-z0-9A-Z_-]+)\\.([a-z\.]{2,6})$")

////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////
                    /** ALERT */

/**  @type {String} Configurate alert element class when created */
export const alertClass = 'alert-error hidden'

/**  @type {String} Configurate input element error class when an error occurs */
export const inputErrorClass = 'input_error'

/**  @type {String} Configurate hidden class name for alert to be removed / added */
export const hiddenClass = 'hidden'

////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////