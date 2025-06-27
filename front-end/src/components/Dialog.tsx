import type React from "react";
import Button from "./Button";

export default function Dialog({dialogRef, children}: {dialogRef: React.RefObject<HTMLDialogElement | null>, children: React.ReactNode}) {
    return (
        <dialog ref={dialogRef}>
            <div className="card">
                {children}
                <Button className="close-dialog-btn" variant="accent" aria-label="Fermer" onClick={(e: React.MouseEvent) => {
                    e.preventDefault();
                    dialogRef.current?.close();
                }}><img src='/images/xmark.svg' alt="Fermer le popup" className="icon" /></Button>
            </div>
        </dialog>
    );
}