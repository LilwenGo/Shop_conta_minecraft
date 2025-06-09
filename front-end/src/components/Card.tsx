import { motion } from "motion/react";
import type React from "react";

export default function Card({children}: {children: React.ReactNode}) {
    return (
        <motion.article
            whileHover={{scale: 1.05, transition: {duration: 0.15}}}
            className="card"
        >{children}</motion.article>
    );
}