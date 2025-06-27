import Button from "./Button";

export default function HttpError({code, children}: {code: string|number, children: string}) {
    return (
        <>
            <h2 className="subtitle">Erreur : {code}</h2>
            <p className="paragraph">{children}</p>
            <Button to="/">Retourer à l'accueil</Button>
        </>
    );
}