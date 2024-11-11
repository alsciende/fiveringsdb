(define (script-fu-rounded-rectangle-cut image drawable rect-width rect-height corner-radius new-width new-height)
  (let* (
          ; Get image dimensions
          (width (car (gimp-image-width image)))
          (height (car (gimp-image-height image)))

          ; Center the rectangle
          (x-pos (/ (- width rect-width) 2))
          (y-pos (/ (- height rect-height) 2))
          )

    (gimp-layer-add-alpha drawable)

    ; Create a new selection using a rounded rectangle
    (gimp-image-select-round-rectangle image CHANNEL-OP-REPLACE x-pos y-pos rect-width rect-height corner-radius corner-radius)

    ; Invert the selection
    (gimp-selection-invert image)

    ; Cut the selection (removes it from the image)
    (gimp-edit-cut drawable)

    ; Crop to selection
    (gimp-image-crop image rect-width rect-height x-pos y-pos)

    ; Resize the image
    (gimp-image-scale image new-width new-height)

    ; Display the result
    (gimp-displays-flush)
    )
  )

; Register the script in GIMP
(script-fu-register
  "script-fu-rounded-rectangle-cut"             ; Script name
  "<Image>/Filters/Custom/Rounded Rectangle Cut" ; Location in menu
  "Selects a rounded rectangle, cuts the outer area, and resizes the image"
  "Alsciende"                                   ; Author
  "Alsciende"                                   ; Copyright
  "2024"                                        ; Date
  "*"                                           ; Image type (works on any)
  SF-IMAGE "Image" 0                            ; The image
  SF-DRAWABLE "Drawable" 0                      ; The drawable (layer)
  SF-VALUE "Cut width" "755"                    ; User inputs new width
  SF-VALUE "Cut height" "1055"                   ; User inputs new height
  SF-VALUE "Corner radius" "50"                 ; User inputs radius
  SF-VALUE "New width" "302"                    ; User inputs new width
  SF-VALUE "New height" "422"                   ; User inputs new height
  )
