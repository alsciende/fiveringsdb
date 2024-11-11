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

(define (script-fu-batch-rounded-rectangle-cut filename rect-width rect-height corner-radius new-width new-height)
  (let* (
          ; Get image
          (image (car (gimp-file-load RUN-NONINTERACTIVE filename filename)))
          (drawable (car (gimp-image-get-active-layer image)))

          ; Create the new PNG file name by replacing the extension with ".png"
;          (dest-filename (string-append (substring filename 0 (- (string-length filename) 4)) ".png"))
          (dest-filename (replace-extension filename ".webp"))

          ; Get image dimensions
          (width (car (gimp-image-width image)))
          (height (car (gimp-image-height image)))

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


(define (glob-rounded-rectangle-cut pattern rect-width rect-height corner-radius new-width new-height)
  (let* ((filelist (cadr (file-glob pattern 1))))
    (while (not (null? filelist))
      (let* ((filename (car filelist)))
        (script-fu-batch-rounded-rectangle-cut filename rect-width rect-height corner-radius new-width new-height)
        )
      (set! filelist (cdr filelist)))))
