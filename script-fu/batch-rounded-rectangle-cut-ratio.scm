(define (find-last-dot filename)
  (let loop ((i (- (string-length filename) 1)))
    (if (>= i 0)
      (if (char=? (string-ref filename i) #\.)
        i
        (loop (- i 1)))
      -1))) ; Return -1 if no dot is found

(define (replace-extension filename new-extension)
  (let ((dot-position (find-last-dot filename)))
    (if (>= dot-position 0)
      (string-append (substring filename 0 dot-position) new-extension)
      (string-append filename new-extension)))) ; Append new extension if no dot is found

(define (script-fu-rounded-rectangle-cut-ratio image drawable rect-ratio corner-radius-ratio new-width new-height)
  (let* (
          ; Get image dimensions
          (width (car (gimp-image-width image)))
          (height (car (gimp-image-height image)))

          (rect-width (* width rect-ratio))
          (rect-height (* height rect-ratio))
          (corner-radius (* height corner-radius-ratio))

          (final-rect-width (if (> width height) rect-height rect-width))
          (final-rect-height (if (> width height) rect-width rect-height))

          (final-new-width (if (> width height) new-height new-width))
          (final-new-height (if (> width height) new-width new-height))

          ; Center the rectangle
          (x-pos (/ (- width final-rect-width) 2))
          (y-pos (/ (- height final-rect-height) 2))
          )

    (gimp-layer-add-alpha drawable)

    ; Create a new selection using a rounded rectangle
    (gimp-image-select-round-rectangle image CHANNEL-OP-REPLACE x-pos y-pos final-rect-width final-rect-height corner-radius corner-radius)

    ; Invert the selection
    (gimp-selection-invert image)

    ; Cut the selection (removes it from the image)
    (gimp-edit-cut drawable)

    ; Crop to selection
    (gimp-image-crop image final-rect-width final-rect-height x-pos y-pos)

    ; Resize the image
    (gimp-image-scale image final-new-width final-new-height)

    ; Display the result
    (gimp-displays-flush)
    )
  )

(define (script-fu-batch-rounded-rectangle-cut filename rect-ratio corner-radius-ratio new-width new-height)
  (let* (
          ; Get image
          (image (car (gimp-file-load RUN-NONINTERACTIVE filename filename)))
          (drawable (car (gimp-image-get-active-layer image)))
          (dest-filename (replace-extension filename ".webp"))
          )

    (script-fu-rounded-rectangle-cut-ratio image drawable rect-ratio corner-radius-ratio new-width new-height)

    (file-webp-save RUN-NONINTERACTIVE image drawable dest-filename dest-filename
      0       ; preset
      FALSE   ; lossless
      90      ; quality
      100     ; alpha-quality
      FALSE   ; anim
      FALSE   ; anim-loop
      FALSE   ; minimize-size
      0       ; kf-distance
      FALSE   ; exif
      FALSE   ; iptc
      FALSE   ; xmp
      0       ; delay
      FALSE   ; force-default
      )
  )
)


(define (glob-rounded-rectangle-cut pattern rect-ratio corner-radius-ratio new-width new-height)
  (let* ((filelist (cadr (file-glob pattern 1))))
    (while (not (null? filelist))
      (let* ((filename (car filelist)))
        (script-fu-batch-rounded-rectangle-cut filename rect-ratio corner-radius-ratio new-width new-height)
        )
      (set! filelist (cdr filelist)))))


; Register the script in GIMP
(script-fu-register
  "script-fu-rounded-rectangle-cut-ratio"             ; Script name
  "<Image>/Filters/Custom/Rounded Rectangle Cut Ratio" ; Location in menu
  "Selects a rounded rectangle, cuts the outer area, and resizes the image"
  "Alsciende"                                   ; Author
  "Alsciende"                                   ; Copyright
  "2024"                                        ; Date
  "*"                                           ; Image type (works on any)
  SF-IMAGE "Image" 0                            ; The image
  SF-DRAWABLE "Drawable" 0                      ; The drawable (layer)
  SF-VALUE "Cut ratio" "0.995"                  ; User inputs cut ratio
  SF-VALUE "Corner radius ratio" "0.03"         ; User inputs radius
  SF-VALUE "New width" "350"                    ; User inputs new width
  SF-VALUE "New height" "500"                   ; User inputs new height
  )
